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
$this->title = 'موضوع جديد';
$thump = Yii::$app->imageresize->thump($model->cover, 150, 100, 'crop');
foreach ($model->postBodies as $data) {
    $model->body .= $data->body;
}
?>
<div class="container m-t-25">
    <div class="post-form col-md-9">
        <div class="white pad">
            <h3><?= Yii::t('app', 'post info') ?></h3>
            <hr/>
            <?php
            $form = ActiveForm::begin(['options' => [
                'id' => 'add-post'
                , 'enctype' => 'multipart/form-data']])
            ?>
            <div id="cover_error" style="display: none;">
                <span class="alert alert-danger" style="display: block; text-align: center;">
            <?= Yii::t('app', 'please add cover image') ?>
            </span>
            </div>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <label><?= Yii::t('app', 'cover image') ?></label>

            <div class="row">
                <div class="col-md-2">
                    <?php echo Html::img($thump, ['class' => 'img-responsive', 'id' => 'coveri']); ?>
                    <input id="covercheck" type="hidden" name="covercheck"
                           value="<?= empty($model->cover) ? '' : '0' ?>">

                    <div id="prev"
                         style="background-color: rgba(0, 0, 0, 0.4);padding: 40px 38px;position: absolute;text-align: center;top: 0; display: none;">
                        <i class="fa fa-spin fa-spinner fa-2x" style="position: relative; top: -10px; color: #fff;"></i>
                    </div>
                    <button class="btn btn-primary btn-block" id="uploadtos3" style="border-radius: 0;">
                        <span><?= Yii::t('app', 'Choose a picture') ?></span>
                    </button>
                </div>
                <div class="col-md-1">
                    <span style="font-size: 30px;top: 22px; position: relative;"><?= Yii::t('app', 'or') ?></span>
                </div>
                <div class="col-md-9">
                    <?= $form->field($model, 'ylink')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'youtube ex')]) ?>
                </div>
            </div>
            <input id="cover_input" name="cover" type="hidden" value=""/>
            <br>
            <?php
            $arr = [];
            foreach ($model->postCategories as $cat_selected) {
                $arr[] = $cat_selected->categoryId;
            }
            $model->categories = $arr;
            ?>
            <label for="post-categories" class="control-label"><?= Yii::t('app', 'Categories') ?></label>
            <?php
            $menu = Category::find()
                ->where("lang = '" . Yii::$app->language . "'")
                ->all();
            $mainMenu = [];
            $i = 2;
            foreach ($menu as $menuData) {
                if ($menuData->parent) {
                    $mainMenu[$menuData->parent][$menuData->id] = $menuData->title;
                }
            }
            $catData = [];
            foreach ($menu as $menuData) {
                if (!$menuData->parent) {
                    $catData[$menuData->title] = $mainMenu[$menuData->id];
                }
            }
            echo Select2::widget([
                'name' => 'Post[categories]',
                'value' => $model->categories,
                'data' => $catData,
                'options' => ['multiple' => true, 'placeholder' => Yii::t('app', 'Categories')],
                'pluginOptions' => [
                    'tags' => false,
                ],
            ]);
            ?>

            <br>
            <?php $keywords = array(); ?>
            <?= $form->field($model, 'body')->textarea(['class' => 'froala-edit']) ?>

            <label><?= Yii::t('app', 'Tags') ?></label>
            <?php
            echo Select2::widget([
                'name' => 'Post[keywords]',
                'value' => $keywords,
                'data' => ArrayHelper::map(Tags::find()
                    ->where("published = 1 AND lang = '" . Yii::$app->language . "'")
                    ->orderBy('name')
                    ->all(), 'id', 'name'),
                'options' => ['multiple' => true, 'placeholder' => Yii::t('app', 'add tags')],
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 20
                ],
            ]);
            ?>
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
                <?=
                Html::submitButton($model->isNewRecord ? Yii::t('app', 'Post') : Yii::t('app', 'Save'), [
                    'id' => 'main-post',
                    'class' => 'btn btn-primary pull-left',
                    'onclick' => $event
                ])
                ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-md-3 res-m-t55">
        <div class="row">
            <div class="white-box" style="border: 1px solid #51B415;">
                <h3 style="margin-top: 10px;"><?= Yii::t('app', 'Note') ?> : </h3>

                <p><?= Yii::t('app', 'Add-Post-Note-1') ?></p>

            </div>
        </div>
        <div class="row m-t-25">
            <div class="white-box">
                <h3 style="margin-top: 10px;"><?= Yii::t('app', 'Tips') ?>: </h3>
                <?php if (Yii::$app->language == 'en') {
                    echo $this->render('tips-en');
                } else {
                    echo $this->render('tips-ar');
                }
                ?>
            </div>
        </div>
    </div>
</div>

<from id="uploadform" method="POST" enctype="multipart/form-data" style="display: none;">
    <input id="files3" type="file"/>
    <input id="type" value="post"/>
</from>