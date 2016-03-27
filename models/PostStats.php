<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_stats".
 *
 * @property integer $postId
 * @property integer $views
 * @property integer $fb_share
 * @property integer $tw_share
 * @property integer $g_share
 * @property integer $comments
 *
 * @property Post $post
 */
class PostStats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId', 'views', 'fb_share', 'tw_share', 'g_share', 'comments'], 'required'],
            [['postId', 'views', 'fb_share', 'tw_share', 'g_share', 'comments'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'postId' => Yii::t('app', 'Post ID'),
            'views' => Yii::t('app', 'Views'),
            'fb_share' => Yii::t('app', 'Fb Share'),
            'tw_share' => Yii::t('app', 'Tw Share'),
            'g_share' => Yii::t('app', 'G Share'),
            'comments' => Yii::t('app', 'Comments'),
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
