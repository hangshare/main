<amp-ad width=300 height=200 layout="responsive"
        type="adsense"
        data-ad-client="ca-pub-6288640194310142"
        data-ad-slot="6189074110">
</amp-ad>
<h1><?= $model->title ?></h1>

<?php

echo $this->render('//amp/post/_external', ['data' => $model]);
?>
<?php
$mo = 'mobamp' . time();
$bodys = Yii::$app->cache->get($mo . '-post-body-' . $model->id);
if ($bodys == false) {
    $bodys = '';
    foreach ($model->postBodies as $data) {
        $bodys = $data->body;

    }
    $bodys = Yii::$app->helper->clearHtml($bodys);
    $bodys = Yii::$app->helper->replaceLinks($bodys);


    $bodys = str_replace('=""', '', $bodys);

    $bodys = ampify($bodys);
    Yii::$app->cache->set($mo . '-post-body-' . $model->id, $bodys, 3000);
}
echo $bodys;
?>

<amp-ad width=300 height=200 layout="responsive"
        type="adsense"
        data-ad-client="ca-pub-6288640194310142"
        data-ad-slot="8394999319">
</amp-ad>


<?php
function ampify($html = '')
{
    # Replace img, audio, and video elements with amp custom elements
    $html = str_ireplace(
        ['<img', '<audio', '/audio>', '<iframe'],
        ['<amp-img', '<amp-audio', '/amp-audio>', '<amp-youtube'],
        $html
    );
    # Add closing tags to amp-img custom element
    $html = preg_replace('/<amp-img(.*?)>/', '<amp-img$1 width="300" height="300" layout="responsive"></amp-img>', $html);
    $html = preg_replace('/<amp-youtube(.*?)>/', '<amp-youtube$1 width="300" height="300" layout="responsive"></amp-youtube>', $html);
    $html = str_replace('src="//www.youtube.com/embed/', 'data-videoid="', $html);
    $html = str_replace('allowfullscreen=""', '', $html);
    $html = str_replace('frameborder="0"', '', $html);
    # Whitelist of HTML tags allowed by AMP
    $html = strip_tags($html, '<h1><h2><h3><h4><h5><h6><a><p><ul><ol><li><blockquote><q><cite><ins><del><strong><em><code><pre><svg><table><thead><tbody><tfoot><th><tr><td><dl><dt><dd><article><section><header><footer><aside><figure><time><abbr><div><span><hr><small><br><amp-img><amp-audio><amp-video><amp-ad><amp-anim><amp-carousel><amp-fit-rext><amp-image-lightbox><amp-instagram><amp-lightbox><amp-twitter><amp-youtube>');
    return $html;
}