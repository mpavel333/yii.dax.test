<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Driver */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'display:none;';
}
if($model->isNewRecord == false){
}

if(Yii::$app->user->identity->isSuperAdmin() == false){
 // $drivers = \yii\helpers\ArrayHelper::getColumn(\app\models\Driver::find()->where(['user_id' => \Yii::$app->user->getId()])->all(), 'data_avto');
} else {
 // $drivers = \yii\helpers\ArrayHelper::getColumn(\app\models\Driver::find()->all(), 'data_avto');
}


$usersQuery = \app\models\User::find()->select(['id', 'name'])->all();

foreach ($usersQuery as $user) {
  $userData[$user->id] = $user->name;
}

//print_r($model); die;

?>
<div class="driver-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
                                    
         <?= $form->field($model, 'name', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

         <?= $form->field($model, 'rows', ['cols' => 12, 'colsOptionsStr' => " ", "checkPermission" => false])->widget(unclead\multipleinput\MultipleInput::className(), [
            'id' => 'my_idchannels',
            'min' => 0,
            'columns' => [
                    [
                        'name' => 'id',
                        'options' => [
                            'type' => 'hidden'
                        ]
                    ],

                    [
                      'name'  => 'user_id',
                      'type' => \kartik\select2\Select2::className(),
                      'title' => Yii::t('app','Пользователь'),
                      'options' => [
                        'data' => $userData,
                        'options' => [
                            'placeholder' => 'Выберите',
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                            'allowClear' => true,
                        ],
                        'pluginEvents' => [
                            'change' => 'function(){ 
                                var value = $(this).val(); 
                                var self = this; 
                                    $.get("structure/get-user?id="+value, function(response){ 
                                        if(response.model){
                                            $(self).parent().parent().parent().find(".list-cell__login input").val(response.model.login);
                                            $(self).parent().parent().parent().find(".list-cell__name input").val(response.model.name);
                                            $(self).parent().parent().parent().find(".list-cell__email input").val(response.model.email);
                                            $(self).parent().parent().parent().find(".list-cell__phone input").val(response.model.phone);
                                        }
                                    }); 
                            }',
                        ]
                    ],
                    ],
                    
                    [
                        'name' => 'login',
                        'title' => Yii::t('app','Логин'),
                        'enableError' => true,
                    ],        
                    [
                        'name' => 'name',
                        'title' => Yii::t('app','ФИО'),
                    ], 

                    [
                        'name' => 'email',
                        'title' => Yii::t('app','Почта'),
                    ], 

                    [
                        'name' => 'phone',
                        'title' => Yii::t('app','Телефон'),
                    ], 

                    [
                        'name' => 'tabel',
                        'title' => Yii::t('app','Табель'),
                    ], 

                    
                ],
            ])->label(false)  ?>
                              



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

