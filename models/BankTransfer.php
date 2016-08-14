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
            'name' => Yii::t('app','Bank.name'),
            'account' =>Yii::t('app','Bank.account'),
            'bank_name' =>Yii::t('app','Bank.bank_name'),
            'bank_branch' =>Yii::t('app','Bank.bank_branch'),
            'IBAN' =>Yii::t('app','Bank.IBAN')
        ];
    }

}
