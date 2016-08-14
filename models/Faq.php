<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $categoryId
 * @property string $question
 * @property string $answer
 * @property string $created_at
 *
 * @property User $user
 * @property string lang
 */
class Faq extends \yii\db\ActiveRecord
{

    public static $CategoryStr = [
        '1' => 'Faq.money',
        '2' => 'Faq.tech',
        '3' => 'Faq.posts',
        '20' => 'Faq.others',
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'categoryId', 'published'], 'integer'],
            [['question'], 'required'],
            [['created_at'], 'safe'],
            [['question'], 'string', 'max' => 500],
            [['lang'], 'string', 'max' => 2],
            [['answer'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Faq.id'),
            'userId' => Yii::t('app', 'Faq.user'),
            'categoryId' => Yii::t('app', 'Faq.type'),
            'question' => Yii::t('app', 'Faq.question'),
            'answer' => Yii::t('app', 'Faq.answer'),
            'created_at' => Yii::t('app', 'Faq.created_at'),
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
