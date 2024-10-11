<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\Holiday */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'hidden';
}
if($model->isNewRecord == false){
}


if(isset($back_data) == false){
    $back_data = '';
    if(isset($_GET['Holiday'])){
        $back_data = json_encode($_GET['Holiday']);
    }
} else {
    $back_data = json_encode($back_data);
}

?>
<div class="holiday-form">
    <?php $form = ActiveForm::begin([]); ?>

    <div class="row">
                     

                            
               
                        
             <?= $form->field($model, 'date', ['cols' => 12, 'colsOptionsStr' => " "])->input('date', [])  ?>
    
        
                



	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>    
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

