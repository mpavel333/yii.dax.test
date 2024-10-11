<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

?>

<?php if($w->pjax): ?>
    <?php Pjax::begin(['id' => $w->id.'-pjax', 'enablePushState' => false]) ?>
<?php endif; ?>

<div class="row">
    <?= $content ?>
</div>

<?php echo LinkPager::widget([
    'pagination' => $dataProvider->pagination,
]); ?>

<?php if($w->pjax): ?>
    <?php Pjax::end() ?>
<?php endif; ?>
