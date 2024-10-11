<?php

use \yii\widgets\ActiveForm;

?>


<?php $form = ActiveForm::begin(['method' => "POST"]) ?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label>Файл</label>
			<input type="file" name="file">
		</div>
	</div>
</div>
<?php ActiveForm::end() ?>