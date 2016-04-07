<li>
    <?php $username = empty($model->username) ? $model->id : $model->username; ?>
    <a href="<?= Yii::$app->urlManager->createUrl(['//user/view', 'id' => $username]) ?>"><?php echo $model->name; ?></a>
</li>


