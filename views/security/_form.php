<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\Security */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'hidden';
}
if($model->isNewRecord == false){
}


if(isset($back_data) == false){
    $back_data = '';
    if(isset($_GET['Security'])){
        $back_data = json_encode($_GET['Security']);
    }
} else {
    $back_data = json_encode($back_data);
}
?>

<div class="card card-shadow m-b-10">
                        

<?php $form = ActiveForm::begin(['action'=>'/security/update?id='.$model->id]); ?>
                        
            <?= $form->field($model, 'token', ['cols' => 12, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
        <?php

            $botName = null;
            $result = false;
            if($model->token){
                try {
                    $response = json_decode(file_get_contents("https://api.telegram.org/bot{$model->token}/getMe"), true);
                    $result = true;
                } catch(\Exception $e) {
                    $result = false;
                }


                if(isset($response['result'])){
                    if(isset($response['result']['username'])){
                        $botName = 'https://t.me/'.$response['result']['username'];
                    }
                }
            }


        ?>
        <?=\Yii::t('app', 'Токен бота клиента')?>
            <div class="col-md-1" style="top: 17px;">
                <a class="btn btn-primary btn-block" data-copy="fld2-text" onclick='
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val("<?=$botName?>").select();
                    document.execCommand("copy");
                    $temp.remove();
                    alert("Скопировано в буфер");
                '><i class="fa fa-copy"></i></a>
            </div>
            <div class="col-md-11">
                <?= Html::input('text', 'link', $botName, ['id' => 'fld-text', 'class' => "form-control", 'disabled' => $result])?>
                <?php if($model->token && $botName == null): ?>
                <span class="text-danger"><?=\Yii::t('app', 'Токен не найден')?></span>
                <?php endif; ?>
            </div>
                            
               
                        
             <?= $form->field($model, 'admin_id', ['cols' => 12, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

             <?= $form->field($model, 'api_token', ['cols' => 12, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
    
        
                



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

