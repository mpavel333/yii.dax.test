<?php


use app\models\Payment;
use app\models\Client;
use yii\helpers\Html;
use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = "Рабочий стол";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);


$userId = (isset($_GET['user_id']) ? $_GET['user_id'] : null);
$dateStart = (isset($_GET['date_start']) ? $_GET['date_start'] : null);
$dateEnd = (isset($_GET['date_end']) ? $_GET['date_end'] : null);
$role = (isset($_GET['role']) ? $_GET['role'] : null);




?>
<div class="row">

<div class="col-md-12">
        <div class="card card-shadow m-b-10">
            <div class="row">
                <?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => "GET", 'action' => ['dashboard/index']]) ?>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Менеджер</label>
                            <?= Html::dropDownList('user_id', (isset($_GET['user_id']) ? $_GET['user_id'] : null), ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Выберите']) ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Табель</label>
                            <?= Html::input('text', 'role', (isset($_GET['role']) ? $_GET['role'] : null), ['class' => 'form-control']) ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Дата С</label>
                            <?= Html::input('date', 'date_start', (isset($_GET['date_start']) ? $_GET['date_start'] : null), ['class' => 'form-control']) ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Дата По</label>
                            <?= Html::input('date', 'date_end', (isset($_GET['date_end']) ? $_GET['date_end'] : null), ['class' => 'form-control']) ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <?= Html::a('<i class="fa fa-times"></i>', ['dashboard/index'], ['class' => 'btn btn-white', 'style' => 'margin-top: 22px;']) ?>
                        <?= Html::submitButton('Применить', ['class' => 'btn btn-success', 'style' => 'margin-top: 22px;']) ?>
                    </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
</div>

</div>

<?php

$flights = \app\models\Flight::find()->joinWith(['user'])->andFilterWhere(['flight.user_id' => $userId])->andFilterWhere(['between', 'flight.date_cr', $dateStart, $dateEnd])->andFilterWhere(['user.role' => $role])->all();

$flights_total_summ = \app\models\Flight::find()->joinWith(['user'])
                                                ->andFilterWhere(['flight.user_id' => $userId])
                                                ->andFilterWhere(['flight.is_payed' => 1])
                                                ->andFilterWhere(['between', 'flight.is_payed_date', $dateStart, $dateEnd])
                                                ->andFilterWhere(['user.role' => $role])
                                                ->all();
?>

                <div class="row">

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header flex-box jc-sb">
                            <div>
                                <p style="font-size: 14px; margin-bottom: 5px; margin-top: 0;">Приход</p>
                                <?php
                                    $debit = array_sum(ArrayHelper::getColumn($flights, 'we'));
                                ?>
                                <b style="font-size: 22px;"><?= Yii::$app->formatter->asDecimal($debit) ?> ₽</b>
                            </div>
                            <div>
                                <p style="font-size: 14px; margin-bottom: 5px; margin-top: 0;">Должны зайти</p>
                                <?php
                                    $debit = array_sum(ArrayHelper::getColumn($flights, 'we'));
                                ?>
                                <b style="font-size: 22px;"><?= Yii::$app->formatter->asDecimal($debit) ?> ₽</b>
                            </div>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 14px; margin-bottom: 5px; margin-top: 0;">Общая дельта</p>
                            <?php
                                $delta = 0;
                                foreach($flights as $flight)
                                {
                                    if($flight->is_payed){
                                        $delta = $delta + (doubleval($flight->payment_out) + doubleval($flight->we));
                                    }
                                }
                            ?>
                            <b style="font-size: 22px;"><?= Yii::$app->formatter->asDecimal($delta) ?> ₽</b>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <p style="font-size: 14px; margin-bottom: 5px; margin-top: 5px;">Расход</p>
                            <?php
                                $credit = array_sum(ArrayHelper::getColumn($flights, 'payment_out') + ArrayHelper::getColumn($flights, 'recoil'));
                            ?>
                            <b style="font-size: 22px;"><?= Yii::$app->formatter->asDecimal($credit) ?> ₽</b>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 14px; margin-bottom: 5px; margin-top: 0;">Зарплата</p>
                            <?php
                                $salary = 0;
                                foreach($flights as $flight)
                                {
                                    if($flight->is_salary_payed){
                                        $salary = $salary + doubleval($flight->salary);
                                    }
                                }
                            ?>
                            <b style="font-size: 22px;"><?= Yii::$app->formatter->asDecimal($salary) ?> ₽</b>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <p style="font-size: 14px; margin-bottom: 5px; margin-top: 5px;">Остаток</p>
                            <b style="font-size: 22px;"><?= Yii::$app->formatter->asDecimal(($debit - $credit)) ?> ₽</b>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 14px; margin-bottom: 5px; margin-top: 0;">Баллы</p>
                            <?php
                                $recoil = 0;
                                foreach($flights as $flight)
                                {
                                    $recoil = $recoil + doubleval($flight->recoil);
                                }
                            ?>
                            <b style="font-size: 22px;"><?= Yii::$app->formatter->asDecimal($recoil) ?> ₽</b>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="card">

                        <div class="card-body">
                            <p style="font-size: 14px; margin-bottom: 5px; margin-top: 0;">Сумма ЗП</p>
                            <?php
                                $salary = 0;
                                foreach($flights_total_summ as $flight)
                                {
                                    //if($flight->is_payed){
                                        $salary = $salary + doubleval($flight->salary);
                                    //}
                                }
                            ?>
                            <b style="font-size: 22px;"><?= Yii::$app->formatter->asDecimal($salary) ?> ₽</b>
                        </div>

                    </div>
                </div>


        
    </div>





<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>

<script>
    var ctx = document.getElementById('charContent').getContext('2d');
    var charContent = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['01-Март', '02-Март', '03-Март', '04-Март', '05-Март', '06-Март', '07-Март'],
            datasets: [{
                label: 'Принято заказов',
                data: [120, 190, 300, 170, 600, 340, 450],
                backgroundColor: "rgba(23, 191, 205,0.6)"
            }]
        }
    });
    var ctx = document.getElementById('charContent2').getContext('2d');
    var charContent = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['01-Март', '02-Март', '03-Март', '04-Март', '05-Март', '06-Март', '07-Март'],
            datasets: [{
                label: 'Прибыль',
                data: [120, 600, 190, 300, 170, 340, 450],
                backgroundColor: "rgba(23, 191, 205,0.6)"
            }, {
                label: 'Сумма заказов',
                data: [190, 120, 600, 300, 340, 450, 170],
                backgroundColor: "rgba(245, 156, 26,0.6)"
            }]
        }
    });
    var ctx = document.getElementById('charContent3').getContext('2d');
    var charContent = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['01-Март', '02-Март', '03-Март', '04-Март', '05-Март', '06-Март', '07-Март'],
            datasets: [{
                label: 'Менеджер',
                data: [120, 600, 170, 190, 300, 340, 450],
                backgroundColor: "rgba(23, 191, 205,0.6)"
            }]
        }
    });
</script>

<?php

$script = <<< JS

// $("#search-form input, #search-form select").change(function(){
    // $(this).submit();
// });

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>

<?php \yii\bootstrap\Modal::begin([
    "id"=>"ajaxCrudModal",
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php \yii\bootstrap\Modal::end(); ?>
