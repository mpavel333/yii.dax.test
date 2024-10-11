<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \yii\web\View $this */
/** @var \app\models\YandexToken $model */

$this->title = "Настройки Yandex API";

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">JavaScript API и HTTP Геокодер</h4>	
			</div>
			<div class="panel-body">
				<?php $form = ActiveForm::begin(['id' => 'main-form', 'method' => "POST"]) ?>
					<div class="row">
						<div class="col-md-12">
							<?= $form->field($model, 'token')->textInput() ?>
							<hr>
							<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
						</div>
					</div>
				<?php ActiveForm::end() ?>
			</div>
		</div>
	</div>
</div>