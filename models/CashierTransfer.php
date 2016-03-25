<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CashierTransfer extends Model {

    public $name, $phone, $address, $cashier_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'phone', 'address', 'cashier_name'], 'required'],
            [['name', 'phone', 'address', 'cashier_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => 'الاسم الرباعي',
            'phone' => 'رقم الهاتف',
            'address' => 'العنوان ( اسم الدولة والمدينة و الحي)',
            'cashier_name' => 'اسم الصراف المراد التحويل عليه'
        ];
    }

}
