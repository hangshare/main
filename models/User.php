<?php

namespace app\models;

use app\components\AwsEmail;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $image
 * @property string $password_hash
 * @property integer $gender
 * @property integer $country
 * @property string $dob
 * @property string $scId
 * @property string $username
 * @property string $created_at
 *
 * @property Post[] $posts
 * @property UserSettings $userSettings
 * @property UserStats $userStats
 * @property UserTransactions[] $userTransactions
 * @property string auth_key
 * @property string lang
 * @property null password_reset_token
 * @property int type
 * @property int plan
 * @property int transfer_type
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_DELETED = 0;
//    public $enableSession = true;
//    private static $users = [];
//    private $_user = false;
    const STATUS_ACTIVE = 10;

    public $password, $password_re, $password_old, $password_new, $year, $day, $month;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'gender', 'month', 'day', 'year', 'country', 'username'], 'required'],
            [['password'], 'required', 'on' => 'signup'],
            [['email', 'username'], 'unique'],
            [['email'], 'email'],
            [['gender', 'plan', 'country', 'transfer_type'], 'integer'],
            [['auth_key', 'dob', 'created_at', 'phone', 'password', 'scId', 'bio', 'plan', 'country', 'transfer_type', 'username','lang'], 'safe'],
            [['name', 'email'], 'string', 'max' => 50],
            [['username'], 'string', 'max' => 20],
            [['username', 'email'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            ['username', 'match', 'pattern' => '/^[a-z0-9_-]{3,16}$/',
                'message' => Yii::t('app', 'User.username.match.message')
            ],
            [['image', 'bio'], 'string', 'max' => 250],
            [['email', 'password_hash'], 'unique', 'targetAttribute' => ['email', 'password_hash'], 'message' => 'The combination of Email, Paypal Email and Password Hash has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'User.id'),
            'name' => Yii::t('app', 'User.name'),
            'email' => Yii::t('app', 'User.email'),
            'image' => Yii::t('app', 'User.image'),
            'password_hash' => Yii::t('app', 'User.passwordHash'),
            'password' => Yii::t('app', 'User.password'),
            'password_new' => Yii::t('app', 'User.newPassword'),
            'password_re' => Yii::t('app', 'User.repeatedPassword'),
            'gender' => Yii::t('app', 'User.gender'),
            'dob' => Yii::t('app', 'User.dob'),
            'password_old' => Yii::t('app', 'User.password_old'),
            'created_at' => Yii::t('app', 'User.created_at'),
            'month' => Yii::t('app', 'User.month'),
            'day' => Yii::t('app', 'User.day'),
            'year' => Yii::t('app', 'User.year'),
            'bio' => Yii::t('app', 'User.bio'),
            'country' => Yii::t('app', 'User.country'),
            'phone' => Yii::t('app', 'User.phone'),
            'username' => Yii::t('app', 'User.username')
        ];
    }


    public function beforeSave($insert)
    {
        if ($insert) {
            $this->password_hash = sha1($this->password);
            $this->transfer_type = 0;
            $this->password_reset_token = sha1(time() . rand(2, 200));
            $this->scId = '';
            $this->type = 1;
            $this->plan = 0;
            $this->lang = Yii::$app->language;

            $this->auth_key = \Yii::$app->security->generateRandomString();
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $settings = new UserSettings;
            $settings->userId = $this->id;
            $settings->newsletter = 1;
            $settings->key = md5(uniqid($this->id, true));
            $settings->verified_email = 0;
            $settings->save();
            $stats = new UserStats;
            $stats->userId = $this->id;
            $stats->post_views = 0;
            $stats->profile_views = 0;
            $stats->total_amount = 0;
            $stats->available_amount = 0;
            $stats->cantake_amount = 0;
            $stats->post_total_views = 0;
            $stats->post_count = 0;
            $stats->save();

            AwsEmail::queueUser($this->id, 'welcome', [
                '__LINK__' => Yii::$app->urlManager->createAbsoluteUrl(['//u/verify', 'key' => $settings->key])
            ]);
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        $time = strtotime($this->dob);
        $this->day = date('d', $time);
        $this->month = (int)date('m', $time);
        $this->year = date('Y', $time);
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $model = static::findOne(['username' => $username]);
        return new static($model);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + 300 >= time();
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }



    // relations

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentMethod()
    {
        return $this->hasOne(TransferMethod::className(), [
            'userId' => 'id',
            'type' => 'transfer_type'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['userId' => 'id'])->select('id,title,cover, urlTitle');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserStats()
    {
        return $this->hasOne(UserStats::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTransactions()
    {
        return $this->hasMany(UserTransactions::className(), ['userId' => 'id']);
    }

}
