<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_body".
 *
 * @property integer $id
 * @property integer $postId
 * @property string $body
 *
 * @property Post $post
 */
class PostBody extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_body';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId', 'body'], 'required'],
            [['postId'], 'integer'],
            [['body'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'PostBody.id'),
            'postId' => Yii::t('app', 'PostBody.postId'),
            'body' => Yii::t('app', 'PostBody.body'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'postId']);
    }
}
