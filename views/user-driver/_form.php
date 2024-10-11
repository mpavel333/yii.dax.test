<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'display:none;';
}
if($model->isNewRecord == false){
}
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div id="div-login" class="col-md-6" style=" ">        
             <?= $form->field($model, 'login')->textInput()  ?>
        </div>
        <div id="div-role" class="col-md-6" style=" ">        
             <?= $form->field($model, 'role')->textInput()  ?>
        </div>
        <div id="div-name" class="col-md-12" style=" ">        
             <?= $form->field($model, 'name')->textInput()  ?>
        </div>
        <div id="div-phone" class="col-md-12" style=" ">        
             <?= $form->field($model, 'phone')->textInput()  ?>
        </div>
        <div id="div-email" class="col-md-12" style=" ">        
             <?= $form->field($model, 'email')->textInput()  ?>
        </div>
        <div id="div-post_address" class="col-md-12" style=" ">        
             <?= $form->field($model, 'post_address')->textInput()  ?>
        </div>
        <div id="div-email" class="col-md-12" style=" ">        
             <?= $form->field($model, 'daks_balance')->textInput()  ?>
        </div>
        <div id="div-access" class="col-md-6" style=" ">      
             <?= $form->field($model, 'access')->checkbox()  ?>
        </div>
        <div id="div-percent_salary" class="col-md-3" style=" ">      
             <?= $form->field($model, 'percent_salary')->textInput(['placeholder' => '50'])  ?>
        </div>
        <div id="div-percent_salary" class="col-md-3" style=" ">      
             <?= $form->field($model, 'percent')->dropDownList(\app\models\User::percentLabels())  ?>
        </div>
    <div class="col-md-12">
        <?php //= $form->field($model, 'role_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Role::find()->all(), 'id', 'name')) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'client_id')->widget(\kartik\select2\Select2::class, [
            'data' => \yii\helpers\ArrayHelper::map(\app\models\Client::find()->all(), 'id', 'name'),
            'options' => [
                'placeholder' => \Yii::t('app', 'Выберите'),
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'password')->textInput(['maxlength' => true, 'data-toggle' => 'tooltip']) ?>

    </div>
    

    <?php if(($model->role_id != 8 || \Yii::$app->user->identity->can('users')) && false): ?>
    <div class="col-md-12">
        <?= $form->field($model, 'mail_host')->textInput() ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'mail_pass')->textInput() ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'mail_port')->textInput() ?>
    </div>

    <div class="col-md-12">
<?= $form->field($model, 'mail_encryption')->dropDownList([
    'ssl' => 'ssl',
    'tls' => 'tls',
]) ?>
    </div>
    <?php endif; ?>

    </div>


    <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<!-- <script src="/libs/jquery.maskedinput.min.js"></script> -->

<!-- <script>$("#client-phone").mask("+7 (999) 999-9999");</script>-->

