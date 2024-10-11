<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Role */
/* @var $form yii\widgets\ActiveForm */

if($model->isNewRecord == false){
    $model->requisite_disallow_fields = is_string($model->requisite_disallow_fields) ? explode(',', $model->requisite_disallow_fields) : [];
    $model->client_disallow_fields = is_string($model->client_disallow_fields) ? explode(',', $model->client_disallow_fields) : [];
    $model->driver_disallow_fields = is_string($model->driver_disallow_fields) ? explode(',', $model->driver_disallow_fields) : [];
    $model->flight_disallow_fields = is_string($model->flight_disallow_fields) ? explode(',', $model->flight_disallow_fields) : [];
    $model->docs = is_string($model->docs) ? explode(',', $model->docs) : [];
    $model->docs1 = is_string($model->docs1) ? explode(',', $model->docs1) : [];
    $model->docs2 = is_string($model->docs2) ? explode(',', $model->docs2) : [];
    $model->docs3 = is_string($model->docs3) ? explode(',', $model->docs3) : [];
    $model->docs_readonly = is_string($model->docs_readonly) ? explode(',', $model->docs_readonly) : [];
    $model->docs1_readonly = is_string($model->docs1_readonly) ? explode(',', $model->docs1_readonly) : [];
    $model->docs2_readonly = is_string($model->docs2_readonly) ? explode(',', $model->docs2_readonly) : [];
    $model->docs3_readonly = is_string($model->docs3_readonly) ? explode(',', $model->docs3_readonly) : [];
}

?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
            <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Реквизиты</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_view_all')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_disallow_fields')->widget(\kartik\select2\Select2::class, [
                        'data' => \app\helpers\AttributesHelper::roleHandle((new \app\models\Requisite())->attributeLabels()),
                        'options' => [
                            'multiple' => true,
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
            <div class="col-md-2">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Организации</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'client_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_view_all')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_limit_visible')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_contract')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_control_all')->checkbox() ?>
                </div>                
                <div class="col-md-12">
                    <?= $form->field($model, 'client_disallow_fields')->widget(\kartik\select2\Select2::class, [
                        'data' => \app\helpers\AttributesHelper::roleHandle((new \app\models\Client())->attributeLabels()),
                        'options' => [
                            'multiple' => true,
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Водители</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_view_all')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_disallow_fields')->widget(\kartik\select2\Select2::class, [
                        'data' => \app\helpers\AttributesHelper::roleHandle((new \app\models\Driver())->attributeLabels()),
                        'options' => [
                            'multiple' => true,
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Автопарк ТО</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'car_to_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'car_to_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'car_to_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'car_to_view_all')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'car_to_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'car_to_responsible')->checkbox() ?>
                </div>
            </div>
        </div>
        

            <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Рейсы</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_view_all')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_payment_check')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_upload_file')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_driver_upload_file')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_is_order')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_is_signature')->checkbox() ?>
                </div>
                <?php /*
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_table')->checkbox() ?>
                </div>
                 */ ?>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_dates')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_manager_change')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_order')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_driver_order')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_driver_signature')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_orders_show')->checkbox() ?>
                </div>
                <?php /*
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_role_table')->checkbox() ?>
                </div>
                 */ ?>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_group_table')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_prepayment')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_statistic')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_print')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_update')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_update_permament')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_export')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_print_pdf')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_copy')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_delete')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_permament_delete')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_archive')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_api')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_btn_signature')->checkbox() ?>
                </div>

                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_check_salary')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_check_recoil')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_check_insurance')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_check_additional_credit')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_date_validation')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_disable_validation')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_checks')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_checks1')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_checks2')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_checks3')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_chat')->checkbox() ?>
                </div>
                <div class="col-md-12"> 
                    <?= $form->field($model, 'flight_archive')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'docs')->widget(\kartik\select2\Select2::class, [
                        'data' => [],
                        'options' => [
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                        ],
                    ])?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'docs1')->widget(\kartik\select2\Select2::class, [
                        'data' => [],
                        'options' => [
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                        ],
                    ])?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'docs2')->widget(\kartik\select2\Select2::class, [
                        'data' => [],
                        'options' => [
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                        ],
                    ])?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'docs3')->widget(\kartik\select2\Select2::class, [
                        'data' => [],
                        'options' => [
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                        ],
                    ])?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_disallow_fields')->widget(\kartik\select2\Select2::class, [
                        'data' => \app\helpers\AttributesHelper::roleHandle((new \app\models\Flight())->attributeLabels()),
                        'options' => [
                            'multiple' => true,
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
<div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Металлика</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'metal_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'metal_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'metal_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'metal_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'metal_view_all')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'metal_disallow_fields')->widget(\kartik\select2\Select2::class, [
                        'data' => \app\helpers\AttributesHelper::roleHandle((new \app\models\Metal())->attributeLabels()),
                        'options' => [
                            'multiple' => true,
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Почта</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'mail_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'mail_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'mail_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'mail_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'mail_view_all')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'mail_disallow_fields')->widget(\kartik\select2\Select2::class, [
                        'data' => \app\helpers\AttributesHelper::roleHandle((new \app\models\Mail())->attributeLabels()),
                        'options' => [
                            'multiple' => true,
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Праздники</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'holiday_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'holiday_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'holiday_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'holiday_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'holiday_view_all')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'holiday_disallow_fields')->widget(\kartik\select2\Select2::class, [
                        'data' => \app\helpers\AttributesHelper::roleHandle((new \app\models\Mail())->attributeLabels()),
                        'options' => [
                            'multiple' => true,
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    
    
         <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Структура компании</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'structure_create')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'structure_update')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'structure_delete')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'structure_view')->checkbox()?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'structure_view_all')->checkbox() ?>
                </div>

            </div>
        </div>   

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'books')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'settings')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'security')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'dashboard')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'flight_export')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'car')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'rent_car')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'security_table')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'login')->checkbox() ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'calls')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'ticket_manager')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'client_work')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'users')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'lawyer')->checkbox() ?>
        </div>
    </div>



    <?php  if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?=  Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php  } ?>

    <?php  ActiveForm::end(); ?>
</div>
