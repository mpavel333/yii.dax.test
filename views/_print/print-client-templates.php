<?php

use yii\helpers\Html;

/** @var $templates \app\models\Template */
/** @var $id int */

?>
<style type="text/css">
	.row-templates .btn {
		margin-bottom: 3px;
	}
</style>

<div class="row row-templates">
<?php foreach ($templates as $template): ?>
		<?php if(in_array($template->id, [17, 18])) { continue; }  ?>

		<?php if(\Yii::$app->controller->id == 'flight'): ?>
			<?php if(in_array($template->id, [11, 12, 20, 21])): ?>
				<div class="col-md-12">
					<?= Html::a($template->name, ['print-client-template', 'id' => $id, 'template_id' => $template->id], ['class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
				</div>
				<div class="col-md-12">
					<?= Html::a($template->name.' (с подписью)', ['print-client-template', 'id' => $id, 'template_id' => $template->id, 'signature' => 1], ['class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
				</div>
			<?php elseif(in_array($template->id, [16])): ?>
				<div class="col-md-12">
					<?= Html::a($template->name, ['print-client-template', 'id' => $id, 'template_id' => $template->id, 'signature' => 1], ['class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
				</div>
				<?php if($template->id == 16): ?>
					<div class="col-md-12">
						<?= Html::a($template->name.' (без подписи)', ['print-client-template', 'id' => $id, 'template_id' => $template->id], ['class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
					</div>
				<?php endif; ?>
			<?php else: ?>
				<div class="col-md-12">
					<?= Html::a($template->name, ['print-client-template', 'id' => $id, 'template_id' => $template->id], ['class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
				</div>
			<?php endif; ?>
		<?php else: ?>
				<div class="col-md-12">
					<?= Html::a($template->name, ['print-client-template', 'id' => $id, 'template_id' => $template->id], ['class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
				</div>
		<?php endif; ?>

<?php endforeach; ?>

