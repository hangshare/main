<?php
$this->title = Yii::t('app', 'h1.suspended');
$this->description = Yii::t('app', 'This account has been suspended due to illigal usage of hangshare website');
?>
<div class="white-box">
    <div class="center">
        <h1 style="color: #ff3d3d; text-align: center;"><?= Yii::t('app', 'h1.suspended') ?></h1>
        <p><?= Yii::t('app', 'This account has been suspended due to illigal usage of hangshare website') ?></p>
        <p><?= Yii::t('app', 'Please contact us if you feel there is a mistake'); ?></p>
    </div>
</div>

<script type="application/javascript">
    window.onload = function() {
        var anchors = document.getElementsByTagName("a");
        for (var i = 0; i < anchors.length; i++) {
            anchors[i].onclick = function() {return(false);};
        }
    };
</script>