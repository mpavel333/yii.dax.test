<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="row">
            <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Реквизиты</h5>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_name')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_doljnost_rukovoditelya')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_fio_polnostyu')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_official_address')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_bank_name')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_inn')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_kpp')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_ogrn')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_bic')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_kr')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_nomer_rascheta')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_tel')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_fio_buhgaltera')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_nds')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'requisite_pechat')->checkbox() ?>
                </div>

            </div>
        </div>
            <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Организации</h5>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <?= $form->field($model, 'client_name')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_doljnost_rukovoditelya')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_fio_polnostyu')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_official_address')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_bank_name')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_inn')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_kpp')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_ogrn')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_bic')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_kr')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_nomer_rascheta')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_tel')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_email')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_nds')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_doc')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_mailing_address')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'client_code')->checkbox() ?>
                </div>

            </div>
        </div>
            <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:#16508A;">Водители</h5>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <?= $form->field($model, 'driver_data')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_driver')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_phone')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'driver_data_avto')->checkbox() ?>
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
                    <?= $form->field($model, 'flight_organization_id')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_zakazchik_id')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_carrier_id')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_driver_id')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_rout')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_order')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_view_auto')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_address1')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_shipping_date')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_telephone1')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_type')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date_out2')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_address_out2')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_contact_out2')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_name2')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_address_out3')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date_out3')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_contact')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_name3')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_address_out4')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date_out4')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_telephone')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_cargo_weight')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_name')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_address_out5')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_contact_out')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date_out5')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_volume')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_address')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date_out6')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_contact_out3')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_dop_informaciya_o_gruze')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_we')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_pay_us')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_payment1')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_col2')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_payment_out')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_otherwise2')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_otherwise3')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_col1')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_fio')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date_cr')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_number')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_upd')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date2')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_date3')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_recoil')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_your_text')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_otherwise4')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_otherwise')->checkbox() ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'flight_file')->checkbox() ?>
                </div>

            </div>
        </div>
    </div>

    <?php  if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?=  Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php  } ?>

    <?php  ActiveForm::end(); ?>
</div>
