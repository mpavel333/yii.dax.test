<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var $templates \app\models\Template */
/** @var $id int */

?>
<style type="text/css">
	.row-templates .btn {
		margin-bottom: 3px;
	}
</style>

<script>

var items_tmpl;
var dragSrcEl = null;

function handleDragStart(e) {
  dragSrcEl = this;
  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragOver(e) {
  if (e.preventDefault) {
	e.preventDefault();
  }
  e.dataTransfer.dropEffect = 'move';
  return false;
}

function handleDragEnter(e) {
  this.classList.add('over');
}

function handleDragLeave(e) {
  this.classList.remove('over');
}

function handleDrop(e) {
  if (e.stopPropagation) {
	e.stopPropagation();
  }
  
  if (dragSrcEl != this) {
	dragSrcEl.innerHTML = this.innerHTML;
	this.innerHTML = e.dataTransfer.getData('text/html');
	this.style.opacity = '0.4';
  }

  let ids = [];
  let ord = [];
  
	items_tmpl = document.querySelectorAll('.row-templates .col-md-12');
	items_tmpl.forEach(function(item,index) {
		ids.push(item.querySelector('a').getAttribute("data-template_id"));
		ord.push(index);
	});

	$.post('/template/update-ord', { ids: ids, ord: ord }, function(response){
		console.log(response, 'response');
	});	

  return false;
}

function handleDragEnd(e) {
  this.style.opacity = '1';
  
  items_tmpl.forEach(function (item) {
	item.classList.remove('over');
  });

}


items_tmpl = document.querySelectorAll('.row-templates .col-md-12');
items_tmpl.forEach(function(item) {
	
  item.addEventListener('dragstart', handleDragStart, false);
  item.addEventListener('dragenter', handleDragEnter, false);
  item.addEventListener('dragover', handleDragOver, false);
  item.addEventListener('dragleave', handleDragLeave, false);
  item.addEventListener('drop', handleDrop, false);
  item.addEventListener('dragend', handleDragEnd, false);
	
});

</script>





<div class="row row-templates">
<?php foreach ($templates as $template): ?>
		<?php if(in_array($template->id, [17, 18])) { continue; }  ?>

		<?php $name = $template->name.' №'.$model->order.' от '. \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y').'г. '.ArrayHelper::getValue($model, 'zakazchik.name') ?>

		<?php if(\Yii::$app->controller->id == 'flight'): ?>
			<?php if(in_array($template->id, [11, 12, 20, 21])): ?>
				<div class="col-md-12">
					<div>
						<?= Html::a($name, ['print-template', 'id' => $id, 'template_id' => $template->id, 'subject' => $name], ['data-template_id' => $template->id, 'class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
					</div>
					<div>
						<?= Html::a($name.' (с подписью)', ['print-template', 'id' => $id, 'template_id' => $template->id, 'signature' => 1, 'subject' => $name], ['data-template_id' => $template->id,'class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
					</div>
				</div>
			<?php elseif(in_array($template->id, [16,28])): ?>

				<?php $name = $template->name.' №'.$model->upd.' от '. \Yii::$app->formatter->asDate($model->date_cr, 'php:d.m.Y').'г. '.$model->rout ?>

				<div class="col-md-12">
					<div>
						<?= Html::a($name, ['print-template', 'id' => $id, 'template_id' => $template->id, 'signature' => 1, 'subject' => $name], ['data-template_id' => $template->id, 'class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
					</div>
					<?php if(in_array($template->id, [16,28])): ?>
					<div>
						<?= Html::a($name.' (без подписи)', ['print-template', 'id' => $id, 'template_id' => $template->id, 'subject' => $name.' (без подписи)'], ['data-template_id' => $template->id, 'class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
					</div>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<div class="col-md-12">
					<?= Html::a($name, ['print-template', 'id' => $id, 'template_id' => $template->id, 'subject' => $name], ['data-template_id' => $template->id, 'class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
				</div>
			<?php endif; ?>
		<?php else: ?>
				<div class="col-md-12">
					<?= Html::a($name, ['print-template', 'id' => $id, 'template_id' => $template->id, 'subject' => $name], ['data-template_id' => $template->id, 'class' => 'btn btn-success btn-block', 'target' => '_blank']); ?>
				</div>
		<?php endif; ?>

<?php endforeach; ?>

<?php

$script = <<< JS




JS;


$this->registerJs($script, \yii\web\View::POS_READY);

?>