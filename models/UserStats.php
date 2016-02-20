<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_stats".
 *
 * @property integer $userId
 * @property integer $post_views
 * @property integer $profile_views
 * @property double $total_amount
 * @property double $available_amount
 * @property double $cantake_amount
 *
 * @property User $user
 */
class UserStats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'post_views', 'profile_views', 'total_amount', 'available_amount', 'cantake_amount'], 'required'],
            [['userId', 'post_views', 'profile_views'], 'integer'],
            [['total_amount', 'available_amount', 'cantake_amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('app', 'User ID'),
            'post_views' => Yii::t('app', 'Post Views'),
            'profile_views' => Yii::t('app', 'Profile Views'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'available_amount' => Yii::t('app', 'Available Amount'),
            'cantake_amount' => Yii::t('app', 'Cantake Amount'),
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
