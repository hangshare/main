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
 */
class Faq extends \yii\db\ActiveRecord {

    public static $CategoryStr = [
        '1' => 'أسئلة المالية',
        '2' => 'أسئلة تقنية',
        '3' => 'أسئلة عن المقالات',
        '20' => 'غير ذلك',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'faq';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['userId', 'categoryId' , 'published'], 'integer'],
            [['question'], 'required'],
            [['created_at'], 'safe'],
            [['question'], 'string', 'max' => 500],
            [['answer'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'userId' => 'User',
            'categoryId' => 'Type',
            'question' => 'هل لديك سؤال جديد ؟',
            'answer' => 'الإجابة',
            'created_at' => 'Added on',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

}
