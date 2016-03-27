<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class BankTransfer extends Model {

    public $name, $account, $bank_name, $bank_branch, $IBAN;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'account', 'bank_name', 'bank_branch', 'IBAN'], 'required'],
            [['name', 'account', 'bank_name', 'bank_branch', 'IBAN'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => 'الاسم الرباعي',
            'account' => 'رقم الحساب',
            'bank_name' => 'اسم البنك',
            'bank_branch' => 'فرع البنك المراد التحويل عليه',
            'IBAN' => 'رقم الـ IBAN'
        ];
    }

}
