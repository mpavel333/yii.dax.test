<?php

use \yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset; 
use yii\widgets\ActiveForm;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\dynagrid\DynaGrid;


$this->title = "Рейсы - Архив";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['archive/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['archive/create'];
}

$clientPayment = isset($_GET['client_payment']) ? boolval($_GET['client_payment']) : false;
$driverPayment = isset($_GET['driver_payment']) ? boolval($_GET['driver_payment']) : false;
$clientPrepayment = isset($_GET['client_prepayment']) ? boolval($_GET['client_prepayment']) : false;
$driverPrepayment = isset($_GET['driver_prepayment']) ? boolval($_GET['driver_prepayment']) : false;

$clientPaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['archive/index', 'client_payment' => ($clientPayment ? 0 : 1)]);
$driverPaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['archive/index', 'driver_payment' => ($driverPayment ? 0 : 1)]);
$clientPrepaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['archive/index', 'client_prepayment' => ($clientPrepayment ? 0 : 1)]);
$driverPrepaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['archive/index', 'driver_prepayment' => ($driverPrepayment ? 0 : 1)]);



if(Yii::$app->user->identity->can('flight_export')){
    $exportButtons = Html::a(Yii::t('app', 'Экспорт').' <i class="fa fa-file-excel-o"></i>', ArrayHelper::merge(['archive/export'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт (второй вариант)').' <i class="fa fa-file-excel-o"></i>', ArrayHelper::merge(['archive/export2'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт (третий вариант)'), ArrayHelper::merge(['archive/export3'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт 4'), ['archive/export4'], ['class' => 'btn btn-warning', 'role' => 'modal-remote']).' '.Html::a(Yii::t('app', 'Экспорт 5'), ['archive/export5'], ['class' => 'btn btn-warning', 'role' => 'modal-remote']).' '.Html::a(Yii::t('app', 'Импорт'), ['archive/add'], ['class' => 'btn btn-warning', 'role' => 'modal-remote']).' '.
        Html::a('Оплата от заказчика', $clientPaymentUrl, ['class' => 'btn btn-'.($clientPayment ? 'danger' : 'outline-secondary'), 'style' => 'margin-left: 25px;']).' '.Html::a('Оплата водителя', $driverPaymentUrl, ['class' => 'btn btn-'.($driverPayment ? 'danger' : 'outline-secondary')]);
} else {
    $exportButtons = '';
}

?>

<style type="text/css">
	.modal-dialog {
		width: 80% !important;
	}
</style>


<div class="row">

<div class="col-md-12" style="margin-bottom: 10px;">

<?php

$wrenchBtn = '';

if(\Yii::$app->user->identity->can('archive_table'))
{
	$wrenchBtn = Html::a('<i class="fa fa-wrench"></i>', '#', ['class' => 'btn btn-danger', 'onclick' => '$(\'#dynagrid-two-1-grid-modal\').modal(\'show\');']);
}

echo Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']).' '.$wrenchBtn.' '.$exportButtons;

?>

<?php  \yii\widgets\Pjax::begin(['id' => 'crud-datatable-archive-pjax']) ?>

<?php   
$script = <<< JS

$('[data-edit="is_payed"]').change(function(){

var attr = $(this).data('edit');
var id = $(this).data('id');
var value = $(this).is(':checked') ? 1 : 0;

if(value){
    $(this).parent().parent().attr('data-payed', 1);
    $(this).parent().parent().attr('data-unpayed', null);
} else {
    $(this).parent().parent().attr('data-payed',null);
    $(this).parent().parent().attr('data-unpayed', 1);
}

$.get('/archive/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
    console.log(response, 'response');
});

});

$('[data-e]').change(function(){

if($(this).attr('type') == 'checkbox'){
    var attr = $(this).data('e');
    var id = $(this).data('id');
    var value = $(this).is(':checked') ? 1 : 0;

    if(value){
        $(this).parent().addClass('success');
    } else {
        $(this).parent().removeClass('success');
    }
}

if($(this).attr('type') == 'input'){
    var attr = $(this).data('e');
    var id = $(this).data('id');
    var value = $(this).val();
}

$.get('/archive/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
    console.log(response, 'response');
});

});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

$drivers = \app\models\Driver::find()->all();

?>

<?php \yii\widgets\Pjax::end() ?>


</div>

<div class="col-md-12">
	<div class="card card-shadow m-b-10">
		<div class="row">
			<?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['archive/index']]) ?>

			<div class="col-md-12">

			<?= $form->field($searchModel, 'status', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
					'data' => [7=>'Поиск а/м',
							   1=>'а/м назначен',
							   3=>'Загрузка',
							   5=>'Выгрузка',
							   6=>'Выполнен'],
                    'options' => ['prompt' => 'Выбрать', 'multiple' => false],
                       'pluginOptions' => [
                              'allowClear' => true,
                              'tags' => false,
                              'tokenSeparators' => [','],
                       ]
                ]) ?>

			<?= $form->field($searchModel, 'user_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
					'data' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'),
                    'options' => ['prompt' => 'Выбрать', 'multiple' => false],
                       'pluginOptions' => [
                              'allowClear' => true,
                              'tags' => false,
                              'tokenSeparators' => [','],
                       ]
                ]) ?>

			<?= $form->field($searchModel, 'date', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->widget(\kartik\daterange\DateRangePicker::class, [
				  'convertFormat'=>true,
                  'pluginEvents' => [
                      'cancel.daterangepicker'=>'function(ev, picker) {}'
                   ],
                   'pluginOptions' => [
                      'opens'=>'right',
                      'locale' => [
                          'cancelLabel' => 'Clear',
                          'format' => 'Y-m-d',
                       ]
                   ]
                ]) ?>

			<?= $form->field($searchModel, 'table', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

			<?= $form->field($searchModel, 'organization_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
                    'options' => ['prompt' => '', 'multiple' => false],
                       'pluginOptions' => [
                              'allowClear' => true,
                              'tags' => false,
                              'tokenSeparators' => [','],
                              'ajax' => [
								'url' => '/requisite/data?attr=name',
							  	'dataType' => 'json',
							  	'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
							],							  
                       ]
                ]) ?>

			<?= $form->field($searchModel, 'ensurance_order', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
			<?= $form->field($searchModel, 'upd', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

			</div>
			
			<div class="col-md-12">
			
			
			<?= $form->field($searchModel, 'rout_from', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->label('Откуда')->textInput() ?>
			<?= $form->field($searchModel, 'rout_to', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->label('Куда')->textInput() ?>

			<?= $form->field($searchModel, 'zakazchik_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
                    'options' => ['prompt' => '', 'multiple' => false],
                       'pluginOptions' => [
                              'allowClear' => true,
                              'tags' => false,
                              'tokenSeparators' => [','],
							  'ajax' => [
								'url' => '/client/data?attr=name',
							  	'dataType' => 'json',
							  	'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
							],
                       ]
                ]) ?>


			<?= $form->field($searchModel, 'carrier_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
                    'options' => ['prompt' => '', 'multiple' => false],
                       'pluginOptions' => [
                              'allowClear' => true,
                              'tags' => false,
                              'tokenSeparators' => [','],
							  'ajax' => [
								'url' => '/client/data?attr=tel',
							  	'dataType' => 'json',
							  	'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
							],
                       ]
                ]) ?>


			<?= $form->field($searchModel, 'driver_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
                    'options' => ['prompt' => '', 'multiple' => false],
                       'pluginOptions' => [
                              'allowClear' => true,
                              'tags' => false,
                              'tokenSeparators' => [','],
							  'ajax' => [
								'url' => '/driver/data?attr=data',
							  	'dataType' => 'json',
							  	'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
							],
                       ]
                ]) ?>

			<?= $form->field($searchModel, 'driver_phone', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
					'data' => \yii\helpers\ArrayHelper::map($drivers, 'phone', 'phone'),
                    'options' => ['prompt' => '', 'multiple' => false],
                    'pluginOptions' => [
                              'allowClear' => true,
                              'tags' => false,
                              'tokenSeparators' => [','],
                       ]
                ])->label('Телефон водителя') ?>

			<?= $form->field($searchModel, 'driver_car_number', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
								'data' => \yii\helpers\ArrayHelper::map($drivers, 'car_number', 'car_number'),
								'options' => ['prompt' => '', 'multiple' => false],
								'pluginOptions' => [
										'allowClear' => true,
										'tags' => false,
										'tokenSeparators' => [','],
								]
							])->label('Номер автомобиля') ?>
			
			</div>
			
			<div class="col-md-12">


			<?= $form->field($searchModel, 'we', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
								'options' => ['prompt' => '', 'multiple' => false],
								'pluginOptions' => [
										'allowClear' => true,
										'tags' => false,
										'tokenSeparators' => [','],
										'ajax' => [
											'url' => '/flight/data?attr=we&key=we',
										  	'dataType' => 'json',
										  	'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
										],
								]
							]) ?>


			<?= $form->field($searchModel, 'pay_us', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
								'options' => ['prompt' => '', 'multiple' => false],
								'pluginOptions' => [
										'allowClear' => true,
										'tags' => false,
										'tokenSeparators' => [','],
										'ajax' => [
											'url' => '/flight/data?attr=pay_us&key=pay_us',
										  	'dataType' => 'json',
										  	'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
										],
								]
							]) ?>

			<?= $form->field($searchModel, 'payment_out', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>							
		
			<?= $form->field($searchModel, 'otherwise2', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
								'options' => ['prompt' => '', 'multiple' => false],
								'pluginOptions' => [
										'allowClear' => true,
										'tags' => false,
										'tokenSeparators' => [','],
										'ajax' => [
											'url' => '/flight/data?attr=otherwise2&key=otherwise2',
										  	'dataType' => 'json',
										  	'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
										],
								]
							]) ?>

			<?= $form->field($searchModel, 'salary', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
								'options' => ['prompt' => '', 'multiple' => false],
								'pluginOptions' => [
										'allowClear' => true,
										'tags' => false,
										'tokenSeparators' => [','],
										'ajax' => [
											'url' => '/flight/data?attr=salary&key=salary',
										  	'dataType' => 'json',
										  	'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
										],
								]
							]) ?>


		<?= $form->field($searchModel, 'is_ati_driver', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	

		<?= $form->field($searchModel, 'is_ati_client', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	

		</div>				


				<div class="col-md-12">
					<hr style="margin-top: 5px; margin-bottom: 15px;">
				</div>

				<div class="col-md-12">
					<div style="float: right;">
						<?= Html::a('Сбросить', ['archive/index'], ['class' => 'btn btn-white']) ?>
						<?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
					</div>
				</div>

			<?php ActiveForm::end() ?>
		</div>
	</div>
</div>    
</div>

<?php if(\Yii::$app->user->identity->can('archive_statistic')): ?>
<div class="card">
<?php

    $archiveRegister = \app\models\Flight::find()->where(['>=', 'created_at', date('Y-m-d').' 00:00:00'])->andWhere(['is_register' => true])->count();
    $archiveToday = \app\models\Flight::find()->where(['>=', 'created_at', date('Y-m-d').' 00:00:00'])->all();
    $users = \app\models\User::find()->where(['id' => array_unique(\yii\helpers\ArrayHelper::getColumn($archiveToday, 'created_by'))])->all();
    $usersMap = [];

    $delta = 0;
    $cargoWeight = 0;
    $salary = 0;

    foreach ($users as $user) {
        $usersMap[$user->id] = $user;
    }

    foreach ($archiveToday as $archive) {
        $user = $usersMap[$archive->created_by];
        if($user){
            $delta = $delta + (doubleval($archive->payment_out) + doubleval($archive->we));
        }
        $archive->cargo_weight = doubleval($archive->cargo_weight);
        if($archive->type_weight == 'tons' && $archive->cargo_weight && is_numeric($archive->cargo_weight)){
            $cargoWeight = $cargoWeight + ($archive->cargo_weight * 1000); 
        }
        if($archive->type_weight == 'kilos' && $archive->cargo_weight && is_numeric($archive->cargo_weight)){
            $cargoWeight = $cargoWeight + $archive->cargo_weight;
        }
        if($archive->salary){
            $salary = $salary + $archive->salary;
        }
    }

?>
    <div class="row">
        <div class="col-md-3">
            <p style="margin-bottom: 0;">Количество заявок в день: <?= number_format(count($archiveToday), 0, '.', ' ') ?></p>
        </div>
        <div class="col-md-3">
            <p style="margin-bottom: 0;">Дельта заявок в день: <?= number_format($delta, 0, '.', ' ') ?></p>
        </div>
        <div class="col-md-3">
            <p style="margin-bottom: 0;">Общая сумма: <?= number_format(($delta - $salary), 0, '.', ' ') ?></p>
        </div>
        <div class="col-md-3">
            <p style="margin-bottom: 0;">Зарлата за день: <?= number_format($salary, 0, '.', ' ') ?> руб.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php \yii\widgets\Pjax::begin(['id' => 'crud-datatable-archive-pjax']) ?>


<div class="row">
	<div class="col-md-12">
		<?php $counter = 1; ?>
		<?php foreach($dataProvider->models as $model): ?>
		<div class="card card-columns">
			<div class="card-column" style="width: 15%;">
				<input type="checkbox" name="test"> №<?= $model->id ?> <span class="pull-right" style="margin-top: 2px;"><?= \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') ?></span>
				<hr>
				<span class="column-title">Статус</span>
				<span>Наша фирма<br><?= ArrayHelper::getValue($model, 'organization.name') ?></span>
				<hr>
				<span><?= $model->rout ?></span><br>
				<span class="label label-accent"><?= $model->distance ?> км</span>
				<hr>
				<span class="column-title">Менеджер:</span>
				<span><?= ArrayHelper::getValue($model, 'user.name') ?></span>
			</div>
			<div class="card-column" style="width: 15%;">
				<span class="column-header">Заказчик</span>
				<span>УПД: <?= $model->upd ?></span><br>
				<span><?= \Yii::$app->formatter->asDate($model->date_cr, 'php:d.m.Y') ?></span>
				<hr>
				<span class="column-title">Заказчик</span>
				<span><?= ArrayHelper::getValue($model, 'zakazchik.name') ?></span>
				<span class="column-title">АТИ заказчика</span>
				<span></span>
				<hr>
				<span>Оплата заказчика</span><br>
				<input type="checkbox" name="test" style="margin-right: 5px;"><span style="font-size: 20px; font-weight: 600;"><?= \Yii::$app->formatter->asDecimal($model->we) ?></span> с НДС
				<div class="form-group" style="margin-top: 5px;">
					<input type="text" name="test" placeholder="Предоплата" class="form-control">
				</div>
			</div>
			<div class="card-column" style="width: 15%;">
				<span class="column-header">Перевозчик</span>
				<span class="column-title">Наименование</span>
				<span><?= \yii\helpers\ArrayHelper::getValue($model, 'carrier.name') ?></span>
				<span class="column-title">Телефон</span>
				<span><?= \yii\helpers\ArrayHelper::getValue($model, 'carrier.contact') ?></span>
				<hr>
				<input type="checkbox" name="test" style="margin-right: 5px;"><span style="font-size: 14px; font-weight: 600;"><?= \Yii::$app->formatter->asDecimal($model->payment_out) ?></span> с НДС
				<div class="form-group" style="margin-top: 5px;">
					<input type="text" name="test" placeholder="Предоплата" class="form-control">
				</div>
			</div>
			<div class="card-column" style="width: 15%;">
				<span class="column-title">Статус</span>
				<?php
		  			if(Yii::$app->user->identity->getRoleName() != "Заказчик"){
          			  echo Html::dropDownList('status'.$model->id, $model->status, \app\models\Flight::statusLabels(), [
          			      'class' => 'form-control',
          			      'prompt' => 'Выберите',
          			      'onchange' => '$.get("/archive/update-attr?id='.$model->id.'&attr=status&value="+$(this).val());',
          			  ]);
          			} else {
          			  echo '<span>'.\yii\helpers\ArrayHelper::getValue(\app\models\Flight::statusLabels(), $model->status).'<span>';
          			}
				?>
				<span class="column-title">Табель</span>
				<span><?= \yii\helpers\ArrayHelper::getValue($model, 'user.role') ?></span>
				<span class="column-title"><?= $model->getAttributeLabel('act') ?></span>
				<span><?php

					echo \app\widgets\AjaxInput::widget([
            		    'model' => $model,
            		    'attr' => 'act',
            		]);

				?></span>
				<span class="column-title"><?= $model->getAttributeLabel('act_date') ?></span>
				<span><?php

					echo \app\widgets\AjaxInput::widget([
		                'model' => $model,
		                'format' => ['date', 'php:d.m.Y'],
		                'type' => 'date',
		                'attr' => 'act_date',
		            ]);

				?></span>
				<span class="column-title">Форма оплаты</span>
				<span><?php

					echo $model->pay_us;

				?></span>

				<span class="column-title"><?= $model->getAttributeLabel('payment_out') ?></span>
				<span><?php
		            $output = number_format(doubleval($model->payment_out), 0, '.', ' ').' руб.'.'<br>';

		            if(Yii::$app->user->identity->can('archive_payment_check')){
		                $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_driver_payed" '.($model->is_driver_payed ? 'checked="true"' : '').'">';
		            }

		            if(Yii::$app->user->identity->can('archive_prepayment')){
		              

		               $dateStr = '';
		               $inputStyle = $model->payment_out_prepayment ? ' background: #fff9f1;' : null;
		              if($model->date3 && $model->col1) {

		                $date2 = new \DateTime($model->date3);

		                if(mb_stripos($model->col1, '-') !== false){
		                  $col1 = explode('-', $model->col1);
		                } elseif(mb_stripos($model->col1, '+') !== false){
		                  $col1 = explode('+', $model->col1);
		                } else {
		                  $col1 = [$model->col1];
		                }
		                $col1 = $col1[count($col1)-1];
		                if(is_numeric($col1) == false){
		                  $output .= "<input type='input' data-id='".$model->id."' data-e='payment_out_prepayment' class='form-control' value='".$model->payment_out_prepayment."' style='padding: 1px 5px; height: 20px; font-size: 10px;".$inputStyle."' placeholder='Предоплата'>";
		                  echo $output;
		                }


		                while($col1 > 0){
		                  $date2->modify("+1 days");
		                  $w = $date2->format('w');
		                  if($w != 6 && $w != 0 && \app\models\Holiday::find()->where(['date' => $date2->format('Y-m-d')])->one() == null){
		                    $col1--;
		                  }
		                }

		                $date2 = $date2->format('Y-m-d');

		                \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');

		                $dateStr = \Yii::$app->formatter->asDate($date2, 'php:d.m.Y');

		              }
		              if($model->payment_out_prepayment_datetime){
		                $output .= "   <span style='font-size: 11px;'>".\Yii::$app->formatter->asDate($model->payment_out_prepayment_datetime, 'php:d.m.Y')."</span><br>";
		              }
		              $output .= "<input type='input' data-id='".$model->id."' data-e='payment_out_prepayment' class='form-control' value='".$model->payment_out_prepayment."' style='padding: 1px 5px; height: 20px; font-size: 10px;".$inputStyle."' placeholder='Предоплата'>".$dateStr;
		            }

		            echo $output;
				?></span>

				<span class="column-title"><?= $model->getAttributeLabel('is_insurance') ?></span>
				<span>
					<?php

						  $output = "<p>{$model->ensurance_prime}</p>";

				          if(\Yii::$app->user->identity->can('archive_check_insurance')){
				            $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_ensurance_payment" '.($model->is_ensurance_payment ? 'checked="true"' : '').'">';
				          }

				          echo $output;

					?>
				</span>
			</div>
			<div class="card-column" style="width: 15%;">
				<span class="column-title"><?= $model->getAttributeLabel('body_type') ?></span>
				<span>
					<?php

						$data = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/cars.php'), 'TypeId', 'Name');
          				echo isset($data[$model->body_type]) ? $data[$model->body_type] : null;

					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('loading_type') ?></span>
				<span>
					<?php

						$data = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/loading-types.php'), 'TypeId', 'Name');
          				echo isset($data[$model->loading_type]) ? $data[$model->loading_type] : null;

					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('uploading_type') ?></span>
				<span>
					<?php

						$data = \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/loading-types.php'), 'TypeId', 'Name');
          				echo isset($data[$model->uploading_type]) ? $data[$model->uploading_type] : null;

					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('shipping_date') ?></span>
				<span>
					<?php

						echo \Yii::$app->formatter->asDate($model->shipping_date, 'php:d.m.Y');

					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('name3') ?></span>
				<span>
					<?php

						$name3 = $model->name3;
			            if(mb_strlen($name3) > 30){
			                $first = mb_substr($name3, 0, 30);
			                $name3 = $first.'<a onclick=\'event.preventDefault(); $("#name3-'.$model->id.'").html($(this).data("text"));\' data-text="'.str_replace('"', "&#34;", $name3).'" class="text-dots">...</a>';

			                echo '<span id="name3-'.$model->id.'">'.$name3.'</span>';
			            }
			            echo $name3;


					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('name3') ?></span>
				<span>
					<?php

						$name3 = $model->name3;
			            if(mb_strlen($name3) > 30){
			                $first = mb_substr($name3, 0, 30);
			                $name3 = $first.'<a onclick=\'event.preventDefault(); $("#name3-'.$model->id.'").html($(this).data("text"));\' data-text="'.str_replace('"', "&#34;", $name3).'" class="text-dots">...</a>';

			                echo '<span id="name3-'.$model->id.'">'.$name3.'</span>';
			            }
			            echo $name3;


					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('cargo_weight') ?></span>
				<span>
					<?php

						echo $model->cargo_weight.' '.ArrayHelper::getValue(app\models\Flight::typeWeightLabels(), $model->type_weight);


					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('information') ?></span>
				<span>
					<?php

						echo $model->information;


					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('recoil') ?></span>
				<span>
					<?php

						$output = "<p>{$model->recoil}</p>";

				          if(\Yii::$app->user->identity->can('archive_check_recoil')){
				            $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_recoil_payment" '.($model->is_recoil_payment ? 'checked="true"' : '').'">';
				          }

				          echo $output;


					?>
				</span>
				<span class="column-title"><?= $model->getAttributeLabel('is_insurance') ?></span>
				<span>
					<?php

						  $output = "<p>{$model->ensurance_prime}</p>";

				          if(\Yii::$app->user->identity->can('archive_check_insurance')){
				            $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_ensurance_payment" '.($model->is_ensurance_payment ? 'checked="true"' : '').'">';
				          }

				          echo $output;


					?>
				</span>
			</div>
			<div class="card-column" style="border-right: none !important;">
				<div>
					<span class="column-header">Водитель</span>
					<div style="float: right; margin-top: -23px;">

						<?php

							$template = '{print-pdf} {api} {copy} {export3} {print} {update} {delete} {archive} {signature}';

							$buttons = [
								'print' => function($model){
					                if((($model->status == 3) || (\Yii::$app->user->identity->isClient() == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin()) && \Yii::$app->user->identity->can('archive_btn_print')){
					                    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['archive/print', 'id' => $model->id], ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Печать']);
					                }
					            },
					            'update' => function($model){

					                $url = ['archive/update', 'id' => $model->id];

					                if(\Yii::$app->controller->id == 'archive-group'){
					                  $url['group'] = true;
					                }

					                if(\Yii::$app->user->identity->can('archive_btn_update') || \Yii::$app->user->identity->can('archive_btn_update_permament')){

					                  if(\Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isSignaturer() || \Yii::$app->user->identity->can('archive_btn_update_permament')){
					                      if(($model->created_by == \Yii::$app->user->getId() && $model->status == 0 && $model->is_signature == false || $model->user_id == Yii::$app->user->getId() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isManager() == false) && (($model->status == 1 && \Yii::$app->user->identity->isClient()) == false && ($model->status == 1 && \Yii::$app->user->identity->isClient() || \Yii::$app->user->identity->isManager()) == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSignaturer() || \Yii::$app->user->identity->can('archive_btn_update_permament')){
					                          return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Редактировать']);
					                      }
					                  }

					                  if(\Yii::$app->user->identity->isClient()){
					                      if($model->status != 1){
					                          return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Редактировать']);
					                      }
					                  }

					                }

					            },
					            'export3' => function($model){
					                if(\Yii::$app->user->identity->isClient() == false && \Yii::$app->user->identity->can('archive_btn_export')){
					                    return Html::a('<i class="fa fa-arrow-right"></i>', ['archive/export3', 'id' => $model->id], ['class' => 'btn btn-sm btn-white','data-pjax' => '0', 'title' => 'Экспорт']);
					                }
					            },
					            'print-pdf' => function($model){
					                if(\Yii::$app->user->identity->isClient() == false && \Yii::$app->user->identity->can('archive_btn_print_pdf')){
					                    return Html::a('<i class="fa fa-file-pdf-o"></i>', ['archive/print-pdf', 'id' => $model->id], ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Экспорт']);
					                }
					            },
					            'copy' => function($model){
					                if(\Yii::$app->user->identity->can('archive_btn_copy')){
					                  return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['archive/copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
					                  	        'class' => 'btn btn-sm btn-white',
					                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
					                            'data-request-method'=>'post',
					                            'data-toggle'=>'tooltip',
					                            'data-confirm-title'=>'Вы уверены?',
					                            'data-confirm-message'=>'Вы уверены что хотите копировать эту позицию?']);
					                }
					            },
					            'delete' => function($model){
					                if(\Yii::$app->controller->id == 'archive-group'){
					                  if(\Yii::$app->user->identity->can('archive_btn_delete')){
					                      return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['archive/delete', 'id' => $model->id], [
					                      	  'class' => 'btn btn-sm btn-white',
					                          'role'=>'modal-remote','title'=>'Удалить', 
					                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
					                          'data-request-method'=>'post',
					                          'data-toggle'=>'tooltip',
					                          'data-confirm-title'=>'Вы уверены?',
					                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию?'
					                      ]);
					                  }
					                } else {
					                    if(((\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && $model->is_signature == false && $model->is_driver_signature == false)) && \Yii::$app->user->identity->can('archive_btn_delete')) || \Yii::$app->user->identity->can('archive_btn_permament_delete')){
					                      return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['archive/delete', 'id' => $model->id], [
					                      	  'class' => 'btn btn-sm btn-white',
					                          'role'=>'modal-remote','title'=>'Удалить', 
					                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
					                          'data-request-method'=>'post',
					                          'data-toggle'=>'tooltip',
					                          'data-confirm-title'=>'Вы уверены?',
					                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию?'
					                      ]);
					                  }
					                }
					            },
					            'archive' => function($model){
					                if((\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && $model->is_signature == false && $model->is_driver_signature == false)) && \Yii::$app->user->identity->can('archive_btn_archive')){
					                    return Html::a('<i class="fa fa-archive"></i>', ['archive/archive', 'id' => $model->id], [
					                    	'class' => 'btn btn-sm btn-white',
					                        'role'=>'modal-remote','title'=>'Архивировать', 
					                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
					                        'data-request-method'=>'post',
					                        'data-toggle'=>'tooltip',
					                        'data-confirm-title'=>'Вы уверены?',
					                        'data-confirm-message'=>'Вы уверены что хотите поместить данный рейс в архив?'
					                    ]);
					                }
					            },
					            'signature' => function($model){
					                if(\Yii::$app->user->identity->can('archive_btn_signature')){
					                    return Html::a('<i class="fa fa-pencil-square-o"></i>', ['archive/signature', 'id' => $model->id], [
					                    	'class' => 'btn btn-sm btn-white',
					                        'role'=>'modal-remote','title'=>'Подписать', 
					                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
					                        'data-request-method'=>'post',
					                        'data-toggle'=>'tooltip',
					                        'data-confirm-title'=>'Вы уверены?',
					                        'data-confirm-message'=>'Вы уверены что хотите подписать данный рейс?'
					                    ]);
					                }
					            },
					            'api' => function($model){
					                    if(\Yii::$app->user->identity->can('archive_btn_api')){
					                      return Html::a('<i class="fa fa-server"></i>', ['archive/api-send', 'id' => $model->id], [
					                      	  'class' => 'btn btn-sm btn-white',
					                          'role'=>'modal-remote','title'=>'API', 
					                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
					                          'data-request-method'=>'post',
					                          'data-toggle'=>'tooltip',
					                          'data-confirm-title'=>'Вы уверены?',
					                          'data-confirm-message'=>'Вы уверены что хотите отправить данный рейс св систему ATI?'
					                      ]); 
					                    }
					            },
							];

							foreach($buttons as $buttonKey => $button)
							{
								$buttonHtml = call_user_func($button, $model);
								$template = str_replace('{'.$buttonKey.'}', $buttonHtml, $template);
							}

							echo $template;

						?>

					</div>
				</div>
				<div class="row">
					<div class="col-md-6" style="margin-bottom: 10px; margin-top: 10px;">
						<span class="column-title">ФИО</span>
						<span><?= \yii\helpers\ArrayHelper::getValue($model, 'driver.data') ?></span>
					</div>
					<div class="col-md-6" style="margin-bottom: 10px; margin-top: 10px;">
						<span class="column-title">АТИ Водителя</span>
						<span></span>
					</div>
					<div class="col-md-6" style="margin-bottom: 10px;">
						<span class="column-title">Телефон</span>
						<span><?= \yii\helpers\ArrayHelper::getValue($model, 'driver.phone') ?></span>
					</div>
					<div class="col-md-6" style="margin-bottom: 10px;">
						<span class="column-title">Данные авто</span>
						<span><?php
							//$driver = \app\models\Driver::find()->where(['id' => $model->auto])->one();
				            //if($driver){
				            //    echo "{$driver->data_avto} {$driver->car_number} {$driver->car_truck_number}";
				            //}  
						?></span>
					</div>
					<div class="col-md-6">
						<span class="column-title">Зарплата</span>
						<span><?= \Yii::$app->formatter->asDecimal($model->salary) ?></span>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6" style="border-right: 1px solid #D8D6DE !important;">
						<span>Информация по рейсу</span>
						<p style="font-size: 10px; color: #6E6B7B; font-weight: 400;">Равным образом реализация намеченных плановых заданий в значительной степени обуславливает создание дальнейших направлений развития.</p>
					</div>
					<div class="col-md-6">
						<span style="margin-bottom: 10px; display: inline-block;">Подписание заявки</span><br>
						<input type="checkbox" name="test"> Подписано
					</div>
				</div>
			</div>
		</div>
<?php
$counter++;
?>
		<?php endforeach; ?>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<?php echo \yii\widgets\LinkPager::widget([
    		'pagination' => $dataProvider->pagination,
		]); ?>
	</div>
</div>

<?php 
$script = <<< JS

$('[data-edit="is_payed"]').change(function(){

var attr = $(this).data('edit');
var id = $(this).data('id');
var value = $(this).is(':checked') ? 1 : 0;

if(value){
    $(this).parent().parent().attr('data-payed', 1);
    $(this).parent().parent().attr('data-unpayed', null);
} else {
    $(this).parent().parent().attr('data-payed',null);
    $(this).parent().parent().attr('data-unpayed', 1);
}

$.get('/archive/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
    console.log(response, 'response');
});

});

$('[data-e]').change(function(){

    if($(this).attr('type') == 'checkbox'){
        var attr = $(this).data('e');
        var id = $(this).data('id');
        var value = $(this).is(':checked') ? 1 : 0;

        if(value){
            $(this).parent().addClass('success');
        } else {
            $(this).parent().removeClass('success');
        }
    }

    if($(this).attr('type') == 'input'){
        var attr = $(this).data('e');
        var id = $(this).data('id');
        var value = $(this).val();
    }

    $.get('/archive/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
        console.log(response, 'response');
    });

});

$('[data-multiple-e]').change(function(){

    var key = $(this).data('multiple-e');
    var attr = $(this).data('attr');
    var id = $(this).data('id');
    var value = $(this).is(':checked');

    $.get('/archive/update-checks?id='+id+'&attr='+attr+'&key='+key+'&value='+value, function(response){
        // console.log(response, 'response');
    });

});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>

<?php \yii\widgets\Pjax::end() ?>

<?php

$checkPks = [];

$session = \Yii::$app->session;

if($session->has('check-archive-session')){
    $checkPks = $session->get('check-archive-session');
}

$checkPks = json_encode($checkPks);

$script = <<< JS

var checkPks = {$checkPks};

$.each(checkPks, function(){
    $('.kv-row-select input[value='+this+']').prop('checked', true);
    $('.kv-row-select input[value='+this+']').trigger('change');
});

$('[data-key]').dblclick(function(e){
    if($(e.target).is('td')){
        var id = $(this).data('key');
        window.location = '/archive/view?id='+id;
    }
});

$(document).on('pjax:complete' , function(event) {
    $('[data-key]').dblclick(function(e){
        if($(e.target).is('td')){
            var id = $(this).data('key');
            window.location = '/archive/view?id='+id;
        }
    });
});

function getChecked(){
    var checked = [];
    $('.kv-row-select input:checked').each(function(){
        checked.push($(this).val());
    });

    $.get('/archive/check-session?pks='+checked.join(','), function(response){

    });
}

$('.kv-row-select input').change(getChecked);
JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
