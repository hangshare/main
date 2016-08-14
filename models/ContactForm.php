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
            'name' => Yii::t('app', 'Contact.name'),
            'email' => Yii::t('app', 'Contact.email'),
            'subject' =>Yii::t('app', 'Contact.subject'),
            'body' =>Yii::t('app', 'Contact.body'),
        ];
    }

}
