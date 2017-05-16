<?php

use app\models\Faq;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'metia.faq.title');
//Yii::t('app', 'Faq Title', ['category' => isset($_GET['category']) ? isset($_GET['category']) : '']);
$this->description = Yii::t('app', 'metia.faq.description');


?>
<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="col-xs-3" style="position: relative;">
                <div style="position: fixed; width: 300px;">
                    <h3><?= Yii::t('app', 'Classifications') ?></h3>

                    <div class="list-group">
                        <?php

                        foreach (Faq::$CategoryStr as $key => $cat) {
                            $cattitle = Yii::t('app', $cat);
                            $catlink = strtolower(Yii::t('app', str_replace(' ', '-', $cattitle)));

                            echo Html::a($cattitle, ["//" . Yii::t('app', 'Faqs-url') . "/" . $catlink], ['title' => $cat, 'class' => 'list-group-item']);
                        }
                        ?>
                    </div>
                    <div style="margin-right: -30px;">
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- Responsive - Faq Right -->
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="ca-pub-6288640194310142"
                             data-ad-slot="8394999319"
                             data-ad-format="auto"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <?php if (isset($tit)) : ?>
                    <h1><?= $tit ?></h1>
                <?php endif; ?>

                <div style="padding: 20px;">
                    <div class="row">
                        <form id="faqsearch">
                            <input name="q" placeholder="<?= Yii::t('app', 'Search') ?>" type="text"/>
                            <input type="submit" name="submit" value="<?= Yii::t('app', 'Search') ?>"/>
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
