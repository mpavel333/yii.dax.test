<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\TicketMessage */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'hidden';
}
if($model->isNewRecord == false){
}


if(isset($back_data) == false){
    $back_data = '';
    if(isset($_GET['TicketMessage'])){
        $back_data = json_encode($_GET['TicketMessage']);
    }
} else {
    $back_data = json_encode($back_data);
}

?>
<div class="ticket-message-form">
    <?php $form = ActiveForm::begin([]); ?>

    <div class="row">
                          

                            
               
                                
        <?php

                        $pluginOptions = [
                  'allowClear' => true,
                ];
        
                $dataModels = \app\models\Ticket::find()->all();

                if(count($dataModels) > 100){
                  $pluginOptions['ajax'] = [
                      'url' => '/ticket/data?attr=subject',
                      'dataType' => 'json',
                      'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                  ];
                }
        ?>

         <?= $form->field($model, 'ticket_id', ['cols' => 12, 'colsOptionsStr' => " "])->widget(Select2::class, [
                    'data' => ArrayHelper::map($dataModels, 'id', 'subject'),
                    'options' => [
                        'placeholder' => \Yii::t('app', 'Выберите'),
                    ],
                    'pluginOptions' => $pluginOptions,
                ]) ?>

                            
               
                        
             <?= $form->field($model, 'text', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
    
                            
               
                              
         <?= $form->field($model, 'application', ['cols' => 12, 'colsOptionsStr' => " "])->textarea(["rows" => 4])  ?>
    
                            
               
                                
        <?php

                        $pluginOptions = [
                  'allowClear' => true,
                ];
        
                $dataModels = \app\models\User::find()->all();

                if(count($dataModels) > 100){
                  $pluginOptions['ajax'] = [
                      'url' => '/user/data?attr=name',
                      'dataType' => 'json',
                      'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                  ];
                }
        ?>

         <?= $form->field($model, 'user_id', ['cols' => 12, 'colsOptionsStr' => " "])->widget(Select2::class, [
                    'data' => ArrayHelper::map($dataModels, 'id', 'name'),
                    'options' => [
                        'placeholder' => \Yii::t('app', 'Выберите'),
                    ],
                    'pluginOptions' => $pluginOptions,
                ]) ?>

                            
               
                          
             <?= $form->field($model, 'create_at', ['cols' => 12, 'colsOptionsStr' => " "])->input('datetime-local')  ?>
    
                            
               
                      
             <?= $form->field($model, 'is_read',['cols' => 12, 'colsOptionsStr' => " "])->checkbox()  ?>
    
        
                



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

