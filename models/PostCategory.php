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
class PostCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'postId', 'categoryId'], 'required'],
            [['id', 'postId', 'categoryId'], 'integer'],
            [['tag'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'PostCategory.id'),
            'postId' => Yii::t('app', 'PostCategory.postId'),
            'tag' => Yii::t('app', 'PostCategory.tag'),
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categoryId'])->select('id,title');
    }
}
