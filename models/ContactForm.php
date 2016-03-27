<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model {

    public $name;
    public $email;
    public $subject;
    public $body;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // name, email, subject and body are required
            [['email', 'body'], 'required'],
            [['name','subject'],'safe'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => 'الإسم',
            'email' => 'البريد الإلكتروني',
            'subject' => 'عنوان الرسالة',
            'body' => 'موضوع الرسالة',
        ];
    }

}
