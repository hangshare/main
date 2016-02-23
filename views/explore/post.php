<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Tags;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'موضوع جديد';
$thump =  Yii::$app->imageresize->thump($model->cover, 150, 100, 'crop');
foreach ($model->postBodies as $data) {
    $model->body .= $data->body;
}
?>
<div class="container m-t-25">
    <div class="post-form col-md-9">
        <div class="white pad">
            <h3>معلومات المقالة</h3>
            <hr />
            <?php
            $form = ActiveForm::begin(['options' => [
                            'id' => 'add-post'
                            , 'enctype' => 'multipart/form-data']])
            ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <label>صورة الغلاف</label>
            <div class="row">
                <div class="col-xs-3">
                    <?php echo Html::img($thump, ['class' => 'img-responsive', 'id' => 'coveri']); ?>
                </div>
                <div class="col-xs-9 centimpost">
                    <input disabled="disabled" placeholder="select file" id="uploadFile">
                    <div class="fileUpload btn btn-primary rela">
                        <span>اختر صورة</span>
                        <?= $form->field($model, 'cover_file')->fileInput()->label(false) ?>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'ylink')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <br>
            <?php
            $keywords = array();
            $tag_selected = array();
            foreach ($model->postTags as $tags) {
                if (isset($tags->tags)) {
                    if ($tags->tags->type == 1) {
                        array_push($tag_selected, $tags->tags->id);
                    } else {
                        array_push($keywords, $tags->tags->id);
                    }
                }
            }
            $model->tags = $tag_selected;
            ?>
            <?= $form->field($model, 'body')->textarea(['class' => 'froala-edit']) ?>
            <?=
            $form->field($model, 'tags')->checkboxList(ArrayHelper::map(
                            Tags::find()
                                    ->where('type=1')
                                    ->all(), 'id', 'name'))
            ?>

            <label>التصنيفات الاضافية</label>
            <?php
//            echo Select2::widget([
//                'name' => 'Post[keywords]',
//                'value' => $keywords,
//                'data' => ArrayHelper::map(Tags::find()
//                                ->where('type=0')
//                                ->orderBy('name')
//                                ->all(), 'id', 'name'),
//                'options' => ['multiple' => true, 'placeholder' => 'اضف الكلمات المناسبة'],
//                'pluginOptions' => [
//                    'tags' => true,
//                    'maximumInputLength' => 10
//                ],
//            ]);
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
                Html::submitButton($model->isNewRecord ? 'انشر المقالة' : 'احفظ التعديلات', [
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
                <h3 style="margin-top: 10px;">ملاحظة : </h3>
                <p>سوف يتم مراجعة وتدقيق المقالة قبل نشرها بشكل رسمي على الموقع</p>
            </div>
        </div>
        <div class="row m-t-25">
            <div class="white-box">
                <h3 style="margin-top: 10px;">نصائح :</h3>
                <p><b>العنوان : </b> إختيار العنوان المناسب يساعد على وصول المقالة لأكبر عدد من الأشخاص المهتمين ، يرجى اختيار جملة قصيرة تتكون من 4 كلمات على الأقل و 10 كلمات كحد أقصى.</p>                
                <p><b>صورة الغلاف : </b> اختيار صورة اغلاف جميلة تجذب الأشخاص الى قراءة مقالتك ، يرجى اختيار صورة يقل حجمها عن 3 MB . </p>
                <p><b>الموضوع: </b> يفضل ان يكون الموضوع متوسط الحجم يزيد عن عشر اسطر ، يمكنك تحميل صور او ارفاق فيديو وتنسيق النص بشكل جميل.</p>
                <p><b>الكلمات المفتاحية : </b> اختيار الكلمات المفتاحية المناسبة تسهل عملية البحث وبالتالي زيادة ظهور المقالة .</p>
            </div>
        </div>
    </div>
</div>