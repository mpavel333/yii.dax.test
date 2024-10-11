<?php


$attribute = $w->attr;

if($w->format){
	$value = \Yii::$app->formatter->format($w->model->$attribute, $w->format);
} else {
	$value = $w->model->$attribute;
}

?>

<div>        	
<?php if($w->model->$attribute): ?>
<a data-edit='<?= $w->id ?>' style="cursor: pointer;"><?= $value ?></a>
<?php else: ?>
<a data-edit='<?= $w->id ?>' style="cursor: pointer;"><i><?= \Yii::t('app', '(Не задано)') ?></i></a>
<?php endif; ?>
<input class="form-control" data-edit-fld='<?= $w->id ?>' style='display: none;' value='<?= $w->model->$attribute ?>' type="<?= $w->type ?>">
</div>

<?php

$script = <<< JS

$('[data-edit="{$w->id}"]').click(function(e){
	e.preventDefault();

	$(this).hide();

	$('[data-edit-fld="{$w->id}"]').show();
});

$('[data-edit-fld="{$w->id}"]').keypress(function(event){
	if(event.keyCode == 13){
		$('[data-edit="{$w->id}"]').html($(this).val());
		$('[data-edit="{$w->id}"]').show();
		$(this).hide();
		$.get('/flight/edit-attr?id={$w->model->id}&attr={$w->attr}&value='+$(this).val(), function(){

		});
	}
});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>