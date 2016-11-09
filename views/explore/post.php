<?php
use app\models\Tags;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
$this->title = Yii::t('app', 'New post');
$thump = Yii::$app->imageresize->thump($model->cover, 150, 100, 'crop');
foreach ($model->postBodies as $data) {
    $model->body .= $data->body;
}
?>
<?php
$form = ActiveForm::begin(['options' => [
    'id' => 'add-post'
    , 'enctype' => 'multipart/form-data']])
?>
<div class="row">
    <div class="col-md-12 res-nopadding">
        <div class="col-md-9" style="background-color: #fff;padding-top: 50px;  min-height: 700px;">
            <div class="container res-nopadding">
                <?= $form->field($model, 'title')->textArea([
                    'maxlength' => 80,
                    'style' => '  border: medium none;box-shadow: none;font-size: 40px;height: 140px;margin-bottom: 20px;padding: 0;resize: none;',
                    'placeholder' => Yii::t('app', 'Add Title')
                ])->label(false) ?>
                <div class="editable"></div>
                <div class="form-group m-t-25">
                    <?php
                    $event = $model->isNewRecord ? "ga('send', {
                            hitType: 'event',
                            eventCategory: 'Post',
                            eventAction: 'Add',
                            eventLabel: 'Add Post'
                        });" : "ga('send', {
                            hitType: 'event',
                            eventCategory: 'Post',
                            eventAction: 'Edit',
                            eventLabel: 'Update Post'
                        });";
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 res-m-t55">
            <div class="white-box text-center">
                <p class="text-center"><?= Yii::t('app', 'Post Quality') ?></p>
                <h2 id="postq" class="text-center" style="font-size: 80px;">0%</h2>
                <?=
                Html::submitButton($model->isNewRecord ? Yii::t('app', 'Post') : Yii::t('app', 'Save'), [
                    'id' => 'main-post',
                    'class' => 'btn btn-primary btn-block',
                    'onclick' => $event
                ])
                ?>
            </div>


            <?php
            $checked = [];
            foreach ($model->postCategories as $cat_selected) {
                $checked[] = $cat_selected->categoryId;
            }
            ?>
            <?php
            $mainMenu = Yii::$app->cache->get('media-data-' . Yii::$app->language);
            if ($mainMenu == false) {
                $menu = Category::find()->where(['lang' => Yii::$app->language])->all();
                $articlesurl = Yii::t('app', 'articles-url');
                $mainMenu = [];
                foreach ($menu as $menuData) {
                    if ($menuData->parent) {
                        $mainMenu[$menuData->parent]['sub'][] = ['id' => $menuData->id, 'title' => $menuData->title, 'url' => $menuData->url_link];
                    } else {
                        $mainMenu[$menuData->id] = ['id' => $menuData->id,
                            'title' => $menuData->title,
                            'url' => $menuData->url_link];
                    }
                }
                Yii::$app->cache->set($mainMenu, 'media-data-' . Yii::$app->language, 3600);
            }
            ?>
            <h3><?= Yii::t('app', 'Categories') ?></h3>
            <div class="padding0" style="background-color: #fff;">
                <div class="tabs-panel">
                    <ul class="categorychecklist form-no-clear list-unstyled">
                        <?php foreach ($mainMenu as $mData) : ?>
                            <li class="popular-category wpseo-term-unchecked">
                                <label class="selectit">
                                    <input class="checkcat"
                                           data-id="category"
                                           type="checkbox" <?php if (in_array($mData['id'], $checked)) echo 'checked="checked"' ?>
                                           name="post_category[]"
                                           value="<?= $mData['id'] ?>">
                                    <?= $mData['title'] ?>
                                </label>
                                <?php if (isset($mData['sub'])): ?>
                                    <ul class="popular-category wpseo-term-unchecked">
                                        <?php foreach ($mData['sub'] as $submenu) : ?>
                                            <li class="popular-category wpseo-term-unchecked">
                                                <label class="selectit">
                                                    <input class="checkcat"
                                                           data-id="category"
                                                           type="checkbox" <?php if (in_array($submenu['id'], $checked)) echo 'checked="checked"' ?>
                                                           name="post_category[]"
                                                           value="<?= $submenu['id'] ?>">
                                                    <?= $submenu['title'] ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>


            <?php
            $tags = [];
            foreach ($model->postTags as $post_tag) {
                $tags[] = $post_tag->tag;
            }
            $tags_string = implode(',', $tags);
            ?>

            <label><?= Yii::t('app', 'Tags') ?></label>
            <?php
            $tags_sql = '';
            if ($tags_string) {
                $tags_sql = "OR id IN ({$tags_string})";
            }
                        echo Select2::widget([
                            'name' => 'Post[keywords]',
                            'value' => $tags,
                            'data' => ArrayHelper::map(Tags::find()
                                ->where("(published = 1 AND lang = '" . Yii::$app->language . "' ) $tags_sql")
                                ->select('id, name')
                                ->orderBy('name')
                                ->all(), 'id', 'name'),
                            'options' => ['multiple' => true, 'placeholder' => Yii::t('app', 'add tags')],
                            'pluginOptions' => [
                                'tags' => true,
                                'maximumInputLength' => 30
                            ],
                        ]);
            ?>

        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<from id="uploadform" method="POST" enctype="multipart/form-data" style="display: none;">
    <input id="files3" type="file"/>
    <input id="type" value="post"/>
</from>