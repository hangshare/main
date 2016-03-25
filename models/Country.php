<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_ar
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_ar'], 'required'],
            [['name', 'name_ar'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_ar' => 'Name Ar',
        ];
    }
}
