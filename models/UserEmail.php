<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_email".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $emailId
 * @property integer $opened_at
 * @property string $key
 * @property string $created_at
 */
class UserEmail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_email';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['userId', 'emailId', 'key'], 'required'],
            [['userId', 'emailId', 'opened_at'], 'integer'],
            [['created_at'], 'safe'],
            [['key'], 'string', 'max' => 50]
        ];
    }
    
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function getTemplate() {
        return $this->hasOne(EmailTemplate::className(), ['id' => 'emailId']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'emailId' => 'Email ID',
            'opened_at' => 'Opened At',
            'key' => 'Key',
            'created_at' => 'Created At',
        ];
    }

}
