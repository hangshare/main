<?php

use yii\helpers\Html;
use app\models\Faq;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "{$tit} - موقع هانج شير";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="col-xs-3" style="position: relative;">
                <div style="position: fixed; width: 300px;">
                    <h3>تصنيفات الأسئلة</h3>
                    <div class="list-group">
                        <?php
                        foreach (Faq::$CategoryStr as $key => $cat) {
                            $catlink = str_replace(' ', '-', $cat);
                            echo Html::a($cat, ["//الأسئلة-الشائعة/{$catlink}"], ['title' => $cat, 'class' => 'list-group-item']);
                        }
                        ?>
                    </div>
                    <div style="margin-right: -30px;">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- faq ad -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:336px;height:280px"
                             data-ad-client="ca-pub-6288640194310142"
                             data-ad-slot="4022492110"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="white-box">
                    <h1><?= $tit ?></h1>
                    <div style="padding: 20px;">
                        <div class="row">
                            <form id="faqsearch">
                                <input name="q" placeholder="ابحث عن سؤال" type="text" />
                                <input  type="submit" name="submit" value="ابحث" />
                            </form>
                            <br>
                        </div>
                        <div id="content">
                            <?=
                            ListView::widget([
                                'dataProvider' => $dataProvider,
                                'itemView' => '_view',
                                'layout' => '{items}{pager}'
                            ]);
                            ?>
                        </div>
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <div class="row m-t-25">
                                <div class="well">
                                    <?= $this->render('_search', ['model' => new Faq]); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
