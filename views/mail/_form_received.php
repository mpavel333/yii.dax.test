<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;
use app\models\Mail;

/* @var $this yii\web\View */
/* @var $model app\models\Mail */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'display:none;';
}
if($model->isNewRecord == false){
}
?>
<div class="mail-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
                       
                
             <?= $form->field($model, 'number', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
    
                
             <?= $form->field($model, 'organization_name', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
    
                
             <?= $form->field($model, 'from', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
    
                
                <div style="display: none;">
                     <?= $form->field($model, 'to', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                </div>
    
                
             <?= $form->field($model, 'track', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

    
                
             <?= $form->field($model, 'when_send', ['cols' => 12, 'colsOptionsStr' => " "])->input('date')  ?>
    
                
             <?= $form->field($model, 'when_receive', ['cols' => 12, 'colsOptionsStr' => " "])->input('date')  ?>

    

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<!-- <script src="/libs/jquery.maskedinput.min.js"></script> -->

<!-- <script>$("#client-phone").mask("+7 (999) 999-9999");</script>-->

