<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "user_transactions".
 *
 * @property integer $userId
 * @property integer $type
 * @property string $info
 * @property User $user
 */
class TransferMethod extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_transfer_method';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['userId', 'type'], 'integer'],
            [['info'], 'string', 'max' => 3000],
            [['info', 'userId', 'type'], 'safe']
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
