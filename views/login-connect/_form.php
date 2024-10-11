<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\LoginConnect */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'hidden';
}
if($model->isNewRecord == false){
}


if(isset($back_data) == false){
    $back_data = '';
    if(isset($_GET['LoginConnect'])){
        $back_data = json_encode($_GET['LoginConnect']);
    }
} else {
    $back_data = json_encode($back_data);
}
?>
<div class="login-connect-form">
    <?php $form = ActiveForm::begin([]); ?>

    <div class="row">
                          

                            
               
                        
             <?= $form->field($model, 'ip_address', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
    
                            
               
                        
             <?= $form->field($model, 'status', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
    
                            
               
                        
             <?= $form->field($model, 'login', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()  ?>
    
                            
               
                        
             <?= $form->field($model, 'password', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
    
                            
               
                        
             <?= $form->field($model, 'code', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
    
                            
               
                          
             <?= $form->field($model, 'create_at', ['cols' => 12, 'colsOptionsStr' => " "])->input('datetime-local')  ?>
    
        
                



	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<script>    $(document).ready(function() {
       $('body').find('input.number').each(function() {
          $(this).val($(this).val().replace(/[^0-9.]/g,'').replace(/\B(?=(\d{3})+(?!\d))/g, " "));
        });
    });
    $('body').on('input', '.number', function(e){
         $(this).val($(this).val().replace(/[^0-9.]/g,'').replace(/\B(?=(\d{3})+(?!\d))/g, " "));
    });
</script>
<!-- <script src="/libs/jquery.maskedinput.min.js"></script> -->

<!-- <script>$("#client-phone").mask("+7 (999) 999-9999");</script>-->

