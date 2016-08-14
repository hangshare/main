<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent
 * @property integer $show_menu
 * @property string $url_link
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'show_menu'], 'integer'],
            [['lang'], 'string', 'max' => 2],
            [['title'], 'string', 'max' => 200],
            [['url_link'], 'string', 'max' => 70]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Category.id'),
            'title' => Yii::t('app', 'Category.title'),
            'parent' => Yii::t('app', 'Category.parent'),
            'show_menu' => Yii::t('app', 'Category.showMenu'),
            'url_link' => Yii::t('app', 'Category.urlLink'),
        ];
    }

}
