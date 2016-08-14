<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CashierTransfer extends Model
{

    public $name, $phone, $address, $cashier_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'address', 'cashier_name'], 'required'],
            [['name', 'phone', 'address', 'cashier_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Cashire.name'),
            'phone' => Yii::t('app', 'Cashire.phone'),
            'address' => Yii::t('app', 'Cashire.address'),
            'cashier_name' => Yii::t('app', 'Cashire.cashier_name')
        ];
    }

}
