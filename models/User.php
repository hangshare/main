<?php

namespace app\models;

use Yii;
use app\models\UserSettings;
use app\models\UserStats;
use app\models\Country;
use app\components\AwsEmail;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $image
 * @property string $paypal_email
 * @property string $password_hash
 * @property integer $gender
 * @property integer $country
 * @property string $dob
 * @property string $scId
 * @property string $created_at
 *
 * @property Post[] $posts
 * @property UserSettings $userSettings
 * @property UserStats $userStats
 * @property UserTransactions[] $userTransactions
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

    public $password, $password_re, $password_old, $password_new, $year, $day, $month;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $auth_key, $username;
    private $_model;

//    public $country;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'email', 'gender', 'month', 'day', 'year', 'country'], 'required'],
            [['password'], 'required', 'on' => 'signup'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['gender', 'plan', 'country', 'transfer_type'], 'integer'],
            [['dob', 'created_at', 'phone', 'password', 'scId', 'bio', 'plan', 'country', 'transfer_type'], 'safe'],
            [['name', 'email'], 'string', 'max' => 50],
            [['paypal_email'], 'email'],
            [['image', 'bio'], 'string', 'max' => 250],
            [['email', 'paypal_email', 'password_hash'], 'unique', 'targetAttribute' => ['email', 'paypal_email', 'password_hash'], 'message' => 'The combination of Email, Paypal Email and Password Hash has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'الإسم'),
            'email' => Yii::t('app', 'البريد الإلكتروني'),
            'image' => Yii::t('app', 'الصورة الشخصية'),
            'paypal_email' => Yii::t('app', ' البريد الالكتروني الخاص بحساب  Paypal'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password' => 'كلمة المرور',
            'password_new' => 'كلمة المرور',
            'password_re' => 'تأكيد كلمة المرور',
            'gender' => Yii::t('app', 'الجنس'),
            'dob' => Yii::t('app', 'تاريخ الميلاد'),
            'password_old' => 'كلمة المرور الحالية',
            'created_at' => Yii::t('app', 'Created At'),
            'month' => 'الشهر',
            'day' => 'اليوم',
            'year' => 'السنة',
            'bio' => 'نبذة مختصرة',
            'country' => 'الدولة',
            'phone' => 'رقم الهاتف',
        ];
    }

    public function afterFind() {
        parent::afterFind();
        $time = strtotime($this->dob);
        $this->day = date('d', $time);
        $this->month = (int) date('m', $time);
        $this->year = date('Y', $time);
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

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
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        //$this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public function beforeSave($insert) {
        if ($insert) {
            $this->password_hash = sha1($this->password);
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            $settings = new UserSettings;
            $settings->userId = $this->id;
            $settings->newsletter = 1;
            $settings->key = md5(uniqid($this->id, true));
            $settings->save();
            $stats = new UserStats;
            $stats->userId = $this->id;
            $stats->post_views = 0;
            $stats->profile_views = 0;
            $stats->total_amount = 0;
            $stats->available_amount = 0;
            $stats->cantake_amount = 0;
            $stats->save();

            AwsEmail::queueUser($this->id, 1, [
                '__LINK__' => Yii::$app->urlManager->createAbsoluteUrl(['//user/verify', 'key' => $settings->key])
            ]);
        }
        return parent::beforeSave($insert);
    }

    function getFirstName() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->firstname;
    }

    function getFullName() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->fullName();
    }

    function getRole() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->role;
    }

    function getPage() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->pagination;
    }

    function getPasswordExpires() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->checkExpiryDate();
    }

    function isAdmin() {
        $user = $this->loadUser();
        if ($user !== null)
            return intval($user->role) == Users::ROLE_ADMIN;
        else
            return false;
    }

    protected function loadUser() {
        if ($this->_model === null) {
            if ($id !== null)
                $this->_model = Users::model()->findByPk(Yii::app()->user->id);
        }
        return $this->_model;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentMethod() {
        return $this->hasOne(TransferMethod::className(), [
                    'userId' => 'id',
                    'type' => 'transfer_type'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts() {
        return $this->hasMany(Post::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings() {
        return $this->hasOne(UserSettings::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserStats() {
        return $this->hasOne(UserStats::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation() {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTransactions() {
        return $this->hasMany(UserTransactions::className(), ['userId' => 'id']);
    }

}
