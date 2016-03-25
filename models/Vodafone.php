<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Vodafone extends Model {

    public $phone;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['phone'], 'required'],
            [['phone'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'phone' => 'رقم الهاتف يبدأ بـ 010',
        ];
    }

}
