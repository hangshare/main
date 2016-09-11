<?php if (!Yii::$app->user->isGuest) : ?>
    <?php $imSm = Yii::$app->imageresize->thump(Yii::$app->user->identity->image, 50, 50, 'crop'); ?>
    <div style="border: 1px solid #eee; padding: 15px;">
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
