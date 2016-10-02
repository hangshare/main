<?php
use yii\helpers\Html;

$width = 500;
$height = 350;
$thump = Yii::$app->imageresize->thump($model->cover, $width, $height, 'crop');
?>

<article class="blog-post-preview margin-bottom-xs-40 col-sm-3 wow fadeInUpLeftScale animated" data-wow-duration="2s" data-wow-delay="1s" style="height: 500px" >
    <a href="<?= $model->url ?>" title="<?= Html::encode($model->title) ?>"
       class="blog-post-preview-link animsition-link"
       data-animsition-out="fade-out-up-sm" data-animsition-out-duration="500">
        <div class="blog-post-preview-img">
            <img class="parallax-img img-responsive skrollable skrollable-before" src="<?= $thump ?>"
                 alt="<?php echo $model->title; ?>"/>
        </div>
        <div>
            <h2 class="blog-post-preview-title font-second"><?php echo $model->title; ?></h2>
        </div>
        <div class="blog-post-preview-date font-second"><?= date('F d, Y', strtotime($model->created_at)) ?></div>
        <div class="blog-post-preview-text">
            <p><?php
                foreach ($model->postBodies as $data) {
                    echo Yii::$app->helper->limit_text($data->body, 150);
                }
                ?></p>
        </div>
    </a>
</article>

