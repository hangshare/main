<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property integer parentId
 * @property string created_at
 * @property string comment
 * @property int postId
 * $property int userId
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment', 'postId'], 'required'],
            [['comment'], 'string', 'max' => 2000],
            [['userId', 'parentId', 'postId'], 'integer']
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->userId = Yii::$app->user->id;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment' => Yii::t('app', 'Comment.comment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplays()
    {
        return $this->hasMany(Comments::className(), ['parentId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId'])->select('id,name,image,bio,plan');
    }
}
