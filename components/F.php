<?php
/**
 * Created by PhpStorm.
 * User: kaldmax
 * Date: 6/19/2016
 * Time: 10:55 PM
 */


namespace app\components;

use Yii;
use app\assets\AppAsset;
use yii\web\View;
use yii\widgets\InputWidget;

class F extends View
{
    /**
     * Load Yii messages for a given category, and makes it available via jQuery.Yii.t(text).<br>
     * NOTE: Just one category can be loaded!
     * @param string $category category of messages
     */
    public function jsTranslations($category)
    {
        $lang = Yii::$app->language;
        $trans = json_encode($this->loadMessages($category));
        $js = "
        jQuery.Yii=
            {
                translations:{$trans},
                t: function(text){
                    if(text in this.translations && this.translations[text])
                        return this.translations[text];
                    return text;
                },
                getLang : function(){
                 return '{$lang}';
                },
            };
        ";

        $view = Yii::$app->getView();
        $view->registerJs($js, View::POS_END);
    }

    protected function loadMessages($category)
    {
        $key = Yii::$app->basePath . '/' . 'messages/' . Yii::$app->language . '/' . $category . '.php';
        return include $key;

    }
}