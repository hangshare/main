<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_tag".
 *
 * @property integer $id
 * @property integer $postId
 * @property string $tag
 *
 * @property Post $post
 */
class PostTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'postId', 'tag'], 'required'],
            [['id', 'postId'], 'integer'],
            [['tag'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'PostTag.id'),
            'postId' => Yii::t('app', 'PostStats.postId'),
            'tag' => Yii::t('app', 'PostStats.tag'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'postId']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags() {
        return $this->hasOne(Tags::className(), ['id' => 'tag'])->select('id,name');
    }
}
