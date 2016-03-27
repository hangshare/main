<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "user_transactions".
 *
 * @property integer $id
 * @property integer $userId
 * @property double $price
 * @property string $payer_email
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $planId
 * @property User $user
 * @property string transactionId
 */
class UserPayment extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['userId', 'start_date', 'end_date'], 'integer'],
            [['price'], 'number'],
            [['transactionId', 'payer_email'], 'string', 'max' => 500],
            [['start_date', 'end_date', 'payer_email', 'start_date','end_date', 'planId', 'transactionId'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

}
