<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class PayPal extends Model {

    public $email, $type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email','type'], 'required'],
            [['type'], 'integer'],
            [['email','type'], 'safe'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'email' => 'البريد الالكتروني الخاص لحساب PayPal',
        ];
    }

}
