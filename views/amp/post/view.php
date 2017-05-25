<?php use yii\helpers\Html; ?>

<article>
    <amp-fit-text>
        <h1><?= Html::encode($this->title) ?></h1>
        <?php
        $mo = 'mob-amp' . time();
        $bodys = Yii::$app->cache->get($mo . '-post-body-' . $model->id);
        if ($bodys == false) {
            $bodys = '';
            foreach ($model->postBodies as $data) {
                $bodys .= $data->body;
            }
            $bodys = Yii::$app->helper->replaceLinks($bodys);

            $bodys = ampify($bodys);


            Yii::$app->cache->set($mo . '-post-body-' . $model->id, $bodys, 3000);
        }
        echo $bodys;
        ?>
    </amp-fit-text>
</article>


<?php

function ampify($html='') {
    # Replace img, audio, and video elements with amp custom elements
    $html = str_ireplace(
        ['<img','<video','/video>','<audio','/audio>','<iframe','/iframe>'],
        ['<amp-img','<amp-video','/amp-video>','<amp-audio','/amp-audio>','<amp-iframe','/amp-iframe>'],
        $html
    );
    # Add closing tags to amp-img custom element
    $html = preg_replace('/<amp-img(.*?)>/', '<amp-img$1 width="300" height="300" layout="responsive"></amp-img>',$html);
    # Whitelist of HTML tags allowed by AMP
    $html = strip_tags($html,'<h1><h2><h3><h4><h5><h6><a><p><ul><ol><li><blockquote><q><cite><ins><del><strong><em><code><pre><svg><table><thead><tbody><tfoot><th><tr><td><dl><dt><dd><article><section><header><footer><aside><figure><time><abbr><div><span><hr><small><br><amp-img><amp-audio><amp-video><amp-ad><amp-anim><amp-carousel><amp-fit-rext><amp-image-lightbox><amp-instagram><amp-lightbox><amp-twitter><amp-youtube>');
    return $html;
}