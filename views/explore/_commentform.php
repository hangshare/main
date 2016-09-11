<?php if (!Yii::$app->user->isGuest) : ?>
    <?php $imSm = Yii::$app->imageresize->thump(Yii::$app->user->identity->image, 50, 50, 'crop'); ?>
    <div style="border-radius: 3px;  margin-bottom: 0;
    margin-top: 20px; background: #fff none repeat scroll 0 0;
    border: 1px solid rgba(0, 0, 0, 0.09);
    border-radius: 3px;
    padding: 20px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);">
        <img width="25" src="<?= $imSm; ?>"> <span><?= Yii::$app->user->identity->name; ?></span>
        <form action="#">
            <textarea date-id="<?= $id ?>" placeholder="<?= Yii::t('app', 'Write a comment ...') ?>"
                      style="font-size:14px; margin: 8px 0; border: 1px solid #ddd; padding-top: 5px;" id="comment-body"
                      name="comment"
                      class="col-md-12"></textarea>
            <a id="addcomment" class="btn btn-primary"><?= Yii::t('app', 'Publish'); ?></a>
        </form>
    </div>
<?php else : ?>

<?php endif; ?>
