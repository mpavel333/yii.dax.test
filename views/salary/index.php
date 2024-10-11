<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SalarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Зарплата';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<div class="card card-shadow m-b-10">

                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'percent')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'percent_with_nds')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'withdraw')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'delta_percent_additional')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'delta_percent')->textInput() ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'delta_percent_no_nds')->textInput() ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'delta_recoil')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'limit')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" style="display: none;">
                            <?php
                                // echo $form->field($model, 'day_pays_min')->input('number')
                            ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'day_pays_between')->input('number') ?>
                        </div>
                    </div>
    
    
                     <div class="row">
                        <div class="col-md-3">
                            <?= $form->field($model, 'carrier_payment_type', ['colsOptionsStr' => " "])->textarea(['rows' => 6])  ?>
                            <p>Перечислите пункты через ";"</p>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'customer_payment_type')->textarea(['rows' => 6]) ?>
                        </div>
                    </div>   

                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>




                    <?php ActiveForm::end(); ?>
                    
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
