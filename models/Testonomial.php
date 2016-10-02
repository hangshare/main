<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property integer $userId
 * @property string $userTitle
 * @property string $bodyText
 * @property string $lang
 *
 * @property User $user
 */
class Testonomial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testonomial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'show_on_home'], 'integer'],
            [['lang'], 'string', 'max' => 2],
            [['userTitle'], 'string', 'max' => 200],
            [['bodyText'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('app', 'User ID'),
            'bodyText' => Yii::t('app', 'Body Text'),
            'userTitle' => Yii::t('app', 'User Title')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
