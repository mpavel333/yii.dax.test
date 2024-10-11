<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="tenants-form">

    <?php $form = ActiveForm::begin(); ?>
  
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'fileUploading', ['cols' => 12, 'colsOptionsStr' => " ", "checkPermission" => false])->fileInput()->label('Файл') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <?= Html::a('Шаблон импорта', ['temp'], ['title'=> 'Шаблон импорта', 'class'=>'btn btn-warning']) ?>
        </div>
    </div>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>