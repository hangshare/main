<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_transactions".
 *
 * @property integer $id
 * @property integer $userId
 * @property double $amount
 * @property string $created_at
 *
 * @property User $user
 */
class UserTransactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'amount'], 'required'],
            [['userId','status'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
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
