<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;



?>

<div class="col-md-<?= $w->colSize ?>">
    <div class="card">
        <div class="card-header">
            <?php /*
            <input type="checkbox" name="" data-id="<?= ArrayHelper::getValue($model, $w->serialAttribute) ?>">
            */ ?>
            <span>#<?= ArrayHelper::getValue($model, $w->serialAttribute) ?></span>
            <div class="card-buttons">
                <?= $buttons ?>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?= ArrayHelper::getValue($model, $w->titleAttribute) ?></h5>
            <ul class="card-list"<?= count($w->listOptions) ? ' '.Html::renderTagAttributes($w->listOptions) : '' ?>>
            	<?php foreach($list as $l): ?>
            		<li>
                    	<div class="item-title"><?= $l['label'] ?></div>
                    	<div class="item-value"><?= $l['content'] ?></div>
                	</li>
            	<?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>