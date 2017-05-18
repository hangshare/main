<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 5/17/2017
 * Time: 5:31 PM
 */

namespace app\components;

use Yii;
use yii\validators\Validator;


class WordValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $total_words = count(preg_split('/\s+/',$model->$attribute));

        if ($total_words > 20) {
            $this->addError($model, $attribute, Yii::t('app', 'wordsvalidations-max'));
        }
        if ($total_words < 5) {
            $this->addError($model, $attribute, Yii::t('app', 'wordsvalidations-min'));
        }
    }


}