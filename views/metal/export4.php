<?php

use yii\helpers\Html;


?>

<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>Дата с</label>
			<input id="dateStartFld" type="date" class="form-control">
		</div>
		<div class="form-group">
			<label>Дата по</label>
			<input id="dateEndFld" type="date" class="form-control">
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<?= Html::a('Экспорт', '#', ['id' => 'exportBtn', 'class' => 'btn btn-block btn-default', 'download' => true]) ?>	
	</div>
</div>

<?php

$script = <<< JS
$('#dateStartFld, #dateEndFld').change(function(){
	var dateStart = $('#dateStartFld').val();
	var dateEnd = $('#dateEndFld').val();

	if(dateStart && dateEnd){
		$('#exportBtn').removeClass('btn-default');
		$('#exportBtn').addClass('btn-success');
		$('#exportBtn').attr('href', '/flight/export-download4?dateStart='+dateStart+'&dateEnd='+dateEnd);
	} else {
		$('#exportBtn').removeClass('btn-success');
		$('#exportBtn').addClass('btn-default');
		$('#exportBtn').attr('href', null);
	}
});
JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>