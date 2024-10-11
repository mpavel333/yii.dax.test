<?php

use \yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset; 
use yii\widgets\ActiveForm;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\dynagrid\DynaGrid;
use app\components\MyCache;
use kartik\select2\Select2;

use app\models\Flight;

$this->title = "Актуальные";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['flight/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['flight/create'];
}

$clientPayment = isset($_GET['client_payment']) ? boolval($_GET['client_payment']) : false;
$driverPayment = isset($_GET['driver_payment']) ? boolval($_GET['driver_payment']) : false;
$clientPrepayment = isset($_GET['client_prepayment']) ? boolval($_GET['client_prepayment']) : false;
$driverPrepayment = isset($_GET['driver_prepayment']) ? boolval($_GET['driver_prepayment']) : false;

$clientPaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['flight/index', 'client_payment' => ($clientPayment ? 0 : 1)]);
$driverPaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['flight/index', 'driver_payment' => ($driverPayment ? 0 : 1)]);
$clientPrepaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['flight/index', 'client_prepayment' => ($clientPrepayment ? 0 : 1)]);
$driverPrepaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['flight/index', 'driver_prepayment' => ($driverPrepayment ? 0 : 1)]);



if(Yii::$app->user->identity->can('flight_export')){
    $exportButtons = Html::a(Yii::t('app', 'Экспорт').' <i class="fa fa-file-excel-o"></i>', ArrayHelper::merge(['flight/export'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт (второй вариант)').' <i class="fa fa-file-excel-o"></i>', ArrayHelper::merge(['flight/export2'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт (третий вариант)'), ArrayHelper::merge(['flight/export3'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт 4'), ['flight/export4'], ['class' => 'btn btn-warning', 'role' => 'modal-remote']).' '.Html::a(Yii::t('app', 'Экспорт 5'), ['flight/export5'], ['class' => 'btn btn-warning', 'role' => 'modal-remote']).' '.Html::a(Yii::t('app', 'Импорт'), ['flight/add'], ['class' => 'btn btn-warning', 'role' => 'modal-remote']).' '.
        Html::a('Оплата от заказчика', $clientPaymentUrl, ['class' => 'btn btn-'.($clientPayment ? 'danger' : 'outline-secondary'), 'style' => 'margin-left: 25px;']).' '.Html::a('Оплата водителя', $driverPaymentUrl, ['class' => 'btn btn-'.($driverPayment ? 'danger' : 'outline-secondary')]);
} else {
    $exportButtons = '';
}

//$role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);
//$roles = \app\models\Role::find()->where(['id' => 1])->all();

$role = \app\models\Role::getRoleFromCache(\Yii::$app->user->identity->role_id);
$roles = \app\models\Role::getRoleFromCache();
//print_r($role); die;

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

if(\Yii::$app->user->identity->can('flight_table'))
{
	//$wrenchBtn = Html::a('<i class="fa fa-wrench"></i>', '#', ['class' => 'btn btn-danger', 'onclick' => '$(\'#dynagrid-two-1-grid-modal\').modal(\'show\');']);
}

echo Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']).' '.$wrenchBtn.' '.$exportButtons;

?>

</div>

<div class="col-md-12">
	<div class="card card-shadow m-b-10">
		<div class="row">
			<?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => [Yii::$app->controller->id.'/index']]) ?>

			<div class="col-md-12">
				<p>
					<?php
						$session = \Yii::$app->session;

						$filtersOpen = false;
						if($session->has('flight-index-filters-open')){
							$filtersOpen = $session->get('flight-index-filters-open');
						}
					
					?>

					<a id="filters-btn-hide" class="pull-right" href="#" onclick="event.preventDefault(); $('#filters-container').hide(); $(this).hide(); $('#filters-btn-show').show(); $.get('/flight/session-toggle-filters?state=0');"<?= $filtersOpen ? null : 'style="display: none;"' ?>><i class="fa fa-minus"></i></a>
					<a id="filters-btn-show" class="pull-right" href="#" onclick="event.preventDefault(); $('#filters-container').show(); $(this).hide(); $('#filters-btn-hide').show(); $.get('/flight/session-toggle-filters?state=1');"<?= $filtersOpen ? 'style="display: none;"' : null ?>><i class="fa fa-plus"></i></a>
				</p>
			</div>

				<div id="filters-container"<?= $filtersOpen ? null : 'style="display: none;"' ?>>

				<div class="col-md-12">

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
		'data' => [],
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

<?= $form->field($searchModel, 'ensurance_order', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
<?= $form->field($searchModel, 'upd', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

</div>

<div class="col-md-12">

<?= $form->field($searchModel, 'rout_from', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->label('Откуда')->textInput() ?>
<?= $form->field($searchModel, 'rout_to', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->label('Куда')->textInput() ?>

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

<?= $form->field($searchModel, 'driver_car_number', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
				'options' => ['prompt' => '', 'multiple' => false],
				'pluginOptions' => [
						'allowClear' => true,
						'tags' => false,
						'tokenSeparators' => [','],
						  'ajax' => [
							  'url' => '/driver/data?attr=car_number&key=car_number',
							'dataType' => 'json',
							'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
						  ],
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

</div>	
                                    
<div class="col-md-12" style="display: none;" >                                    
    <div class="col-md-12">
        <b>Дата З\П:</b>
    </div>
    <?= $form->field($searchModel, 'salary_from', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->label('От')->textInput() ?>
    <?= $form->field($searchModel, 'salary_to', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->label('До')->textInput() ?>

</div> 
				
				</div>		

				<div class="col-md-12">
					<hr style="margin-top: 5px; margin-bottom: 15px;">
				</div>

				<div class="col-md-12">
					<div style="float: right;">
						<?= Html::a('Сбросить', ['flight/index'], ['class' => 'btn btn-white']) ?>
						<?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
					</div>
				</div>

			<?php ActiveForm::end() ?>
		</div>
	</div>
</div>    
</div>



<?php if(\Yii::$app->user->identity->can('flight_statistic')): ?>
<div class="card">
<?php

    $flightRegister = \app\models\Flight::find()->where(['>=', 'created_at', date('Y-m-d').' 00:00:00'])->andWhere(['is_register' => true])->count();
    //$flightToday = \app\models\Flight::find()->where(['>=', 'created_at', date('Y-m-d').' 00:00:00'])->all();
    $flightToday = \app\models\Flight::find()->where(['>=', 'created_at', date('Y-m-d').' 00:00:00'])
                                             ->where(['=', 'user_id', \Yii::$app->user->id])
                                             ->all();
    $users = \app\models\User::find()->where(['id' => array_unique(\yii\helpers\ArrayHelper::getColumn($flightToday, 'created_by'))])->all();
    $usersMap = [];

    $delta = 0;
    $cargoWeight = 0;
    $salary = 0;

    foreach ($users as $user) {
        $usersMap[$user->id] = $user;
    }

    foreach ($flightToday as $flight) {
        $user = $usersMap[$flight->created_by];
        if($user){
            $delta = $delta + (doubleval($flight->payment_out) + doubleval($flight->we));
        }
        $flight->cargo_weight = doubleval($flight->cargo_weight);
        if($flight->type_weight == 'tons' && $flight->cargo_weight && is_numeric($flight->cargo_weight)){
            $cargoWeight = $cargoWeight + ($flight->cargo_weight * 1000); 
        }
        if($flight->type_weight == 'kilos' && $flight->cargo_weight && is_numeric($flight->cargo_weight)){
            $cargoWeight = $cargoWeight + $flight->cargo_weight;
        }
        if($flight->salary){
            $salary = $salary + $flight->salary;
        }
    }

?>
    <div class="row">
        <div class="col-md-3">
            <p style="margin-bottom: 0;">Количество заявок в день: <?= number_format(count($flightToday), 0, '.', ' ') ?></p>
        </div>
        <div class="col-md-3">
            <p style="margin-bottom: 0;">Дельта заявок в день: <?= number_format($delta, 0, '.', ' ') ?></p>
        </div>
        <div class="col-md-3">
            <p style="margin-bottom: 0;">Зарлата за день: <?= number_format($salary, 0, '.', ' ') ?> руб.</p>
        </div>
        <div class="col-md-3">
            <p style="margin-bottom: 0;">Общая сумма: <?= number_format(($delta - $salary), 0, '.', ' ') ?></p>
        </div>
	</div>
</div>
<?php endif; ?>

<?php \yii\widgets\Pjax::begin(['id' => 'crud-datatable-flight-pjax']) ?>


<div class="row">
	<div class="col-md-12">

		<div class="card card-columns">
			<div class="card-column" style="">

				<div class="card-box">
					<div><input type="checkbox" id="check_all"></div>


					<div class="padding-0-5">
<?php

if(((\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager())) && \Yii::$app->user->identity->can('flight_btn_delete')) || 
\Yii::$app->user->identity->can('flight_btn_permament_delete')){

	//<button type="button" data-id="'.$model->id.'" val="'.($model->is_signature ? '' : '1').'" data-e="is_signature" class="btn_is_signature btn '.($model->is_signature ? 'btn-primary' : 'btn-secondary').'">Подписано</button>
	/*
	echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['flight/bulk-delete', 'pks' => '111,333'], [
			'id' => "delete_checks",
			'class' => 'btn btn-danger',
			'role'=>'modal-remote','title'=>'Удалить выделенные записи?', 
			'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
			'data-request-method'=>'post',
			'data-toggle'=>'tooltip',
			'data-confirm-title'=>'Вы уверены?',
			'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию?'
	]);
	*/
	echo Html::button('<span class="glyphicon glyphicon-trash"></span>', [
		'id' => "delete_checks",
		'class' => 'btn btn-danger',
	]);



}
	?>
					</div>
                                        
                                        <div class="padding-0-5">
                                            <?php
                                                    echo Html::button('З/П', [
                                                            'id' => "salary_filter",
                                                            'class' => 'btn btn-success',
                                                    ]);
                                            ?>
                                        </div>
                                        
                                        <div class="box-salary <?=($searchModel->salary_from or $searchModel->salary_to)? 'active' : ''; ?>">                                    
                                            <div class="col-md-12">
                                                <b>Дата З\П:</b>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="text" id="salary_from" class="form-control" value="<?=$searchModel->salary_from ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="salary_to" class="form-control" value="<?=$searchModel->salary_to ?>">
                                            </div>

                                        </div>                                          

				</div>

			</div>	

			
		
		</div>

	</div>

</div>

<div class="row">
	<div class="col-md-12">

<?php
$driverData = [];

foreach (\app\models\Driver::find()->all() as $driver) {
	$driverData[$driver->id] = $driver->data; 
}
?>


		<?php $counter = 1; ?>
		<?php foreach($dataProvider->models as $model): ?>
		<div id="card-<?=$model->id?>" class="card card-columns">


		<div class="card-column">
				
				<div class="row">
					<div class="col-md-12" style="margin-bottom: 10px; margin-top: 10px;">

					<?php

					$template = '{update-files} {print-pdf} {api} {copy} {export3} {print} {update} {delete} {archive} {signature} {chat} {xml} {lawyer} {history}';

					$buttons = [

						'update-files' => function($model){

							$url = ['flight/update-files', 'id' => $model->id];

							if(\Yii::$app->controller->id == 'flight-group'){
							$url['group'] = true;
							}

							if(\Yii::$app->user->identity->can('flight_btn_update') || \Yii::$app->user->identity->can('flight_btn_update_permament')){

							if(\Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isSignaturer() || \Yii::$app->user->identity->can('flight_btn_update_permament')){
								if(($model->created_by == \Yii::$app->user->getId() && $model->status == 0 && $model->is_signature == false || $model->user_id == Yii::$app->user->getId() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isManager() == false) && (($model->status == 1 && \Yii::$app->user->identity->isClient()) == false && ($model->status == 1 && \Yii::$app->user->identity->isClient() || \Yii::$app->user->identity->isManager()) == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSignaturer() || \Yii::$app->user->identity->can('flight_btn_update_permament')){
									return Html::a('<i class="glyphicon glyphicon-file"></i>', $url, ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Загрузка файлов']);
								}
							}

							if(\Yii::$app->user->identity->isClient()){
								if($model->status != 1){
									return Html::a('<i class="glyphicon glyphicon-file"></i>', $url, ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Загрузка файлов']);
								}
							}

							}

						},

						'print' => function($model){
							if((($model->status == 3) || (\Yii::$app->user->identity->isClient() == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin()) && \Yii::$app->user->identity->can('flight_btn_print')){
								return Html::a('<span class="glyphicon glyphicon-print"></span>', ['flight/print', 'id' => $model->id], ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Печать']);
							}
						},
						'update' => function($model){

							$url = ['flight/update', 'id' => $model->id];

							if(\Yii::$app->controller->id == 'flight-group'){
							$url['group'] = true;
							}

							if(\Yii::$app->user->identity->can('flight_btn_update') || \Yii::$app->user->identity->can('flight_btn_update_permament')){

							if(\Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isSignaturer() || \Yii::$app->user->identity->can('flight_btn_update_permament')){
								if(($model->created_by == \Yii::$app->user->getId() && $model->status == 0 && $model->is_signature == false || $model->user_id == Yii::$app->user->getId() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isManager() == false) && (($model->status == 1 && \Yii::$app->user->identity->isClient()) == false && ($model->status == 1 && \Yii::$app->user->identity->isClient() || \Yii::$app->user->identity->isManager()) == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSignaturer() || \Yii::$app->user->identity->can('flight_btn_update_permament')){
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
							if(\Yii::$app->user->identity->isClient() == false && \Yii::$app->user->identity->can('flight_btn_export')){
								return Html::a('<i class="fa fa-arrow-right"></i>', ['flight/export3', 'id' => $model->id], ['class' => 'btn btn-sm btn-white','data-pjax' => '0', 'title' => 'Экспорт']);
							}
						},
						'print-pdf' => function($model){
							if(\Yii::$app->user->identity->isClient() == false && \Yii::$app->user->identity->can('flight_btn_print_pdf')){
								return Html::a('<i class="fa fa-file-pdf-o"></i>', ['flight/print-pdf', 'id' => $model->id], ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Экспорт']);
							}
						},
						'copy' => function($model){
							if(\Yii::$app->user->identity->can('flight_btn_copy')){
							return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['flight/copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
										'class' => 'btn btn-sm btn-white',
										'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
										'data-request-method'=>'post',
										'data-toggle'=>'tooltip',
										'data-confirm-title'=>'Вы уверены?',
										'data-confirm-message'=>'Вы уверены что хотите копировать эту позицию?']);
							}
						},
						'delete' => function($model){
							if(\Yii::$app->controller->id == 'flight-group'){
							if(\Yii::$app->user->identity->can('flight_btn_delete')){
								return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['flight/delete', 'id' => $model->id], [
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
								if(((\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && $model->is_signature == false && $model->is_driver_signature == false)) && \Yii::$app->user->identity->can('flight_btn_delete')) || \Yii::$app->user->identity->can('flight_btn_permament_delete')){
								return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['flight/delete', 'id' => $model->id], [
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
							if((\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && $model->is_signature == false && $model->is_driver_signature == false)) && \Yii::$app->user->identity->can('flight_btn_archive')){
								return Html::a('<i class="fa fa-archive"></i>', ['flight/archive', 'id' => $model->id], [
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
							if(\Yii::$app->user->identity->can('flight_btn_signature')){
								return Html::a('<i class="fa fa-pencil-square-o"></i>', ['flight/signature', 'id' => $model->id], [
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
								if(\Yii::$app->user->identity->can('flight_btn_api')){
								return Html::a('<i class="fa fa-server"></i>', ['flight/api-send', 'id' => $model->id], [
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
						'upd' => function($model){
							return Html::a('<i class="fa fa-file"></i>', ['flight/upd-download', 'id' => $model->id], [
								'data-pjax'=>0,'title'=>'УПД', 'download' => "upd{$model->id}.xml", 
								'class' => 'btn btn-sm btn-white',
							]); 
						},
						'chat' => function($model){
							//$role = \app\models\Role::findOne(Yii::$app->user->identity->role_id);
							$role = \app\models\Role::getRoleFromCache(\Yii::$app->user->identity->role_id);
							if($role && $role->flight_chat){
								return Html::a('<i class="fa fa-weixin"></i>', ['flight/chat-users', 'id' => $model->id], [
									'class' => 'btn btn-sm btn-white',
									'role'=>'modal-remote','title'=>'Чат'
								]);
							}
						},

						'xml' => function($model){

							return '<a data-id="'.$model->id.'" href="#" onclick="event.preventDefault();" class="btn btn-sm btn-white ZipXmlDomnload" title="Скачать XML файлы"><i class="fa fa-code"></i></a>';
							/*
							Html::a('<i class="fa fa-code"></i>', ['#'], [
								'class' => 'btn btn-sm btn-white ZipXmlDomnload',
								'title'=>'Скачать XML файлы', 
								'onclick' => 'event.preventDefault();'],
								['data-id'=>$model->id]
							);
							*/ 
						},

						'history' => function($model){

							return Html::a('H', ['flight/history', 'id' => $model->id], [
								'class' => 'btn btn-sm btn-white',
								'role'=>'modal-remote','title'=>'Чат'
							]);
							/*
							Html::a('<i class="fa fa-code"></i>', ['#'], [
								'class' => 'btn btn-sm btn-white ZipXmlDomnload',
								'title'=>'Скачать XML файлы', 
								'onclick' => 'event.preventDefault();'],
								['data-id'=>$model->id]
							);
							*/ 
						},

						/*
						'xml' => function($model){
							return Html::a('<i class="fa fa-code"></i>', ['flight/zip-xml-download', 'id' => $model->id], [
								'class' => 'btn btn-sm btn-white',
								'data-pjax'=>0,'title'=>'Скачать XML файлы', 'download' => $model->number.".zip", 
							]); 
						},
						*/
						
						// 'xml' => function($model){
						// 	return Html::a('<i class="fa fa-code"></i>', ['flight/zip-xml-download', 'id' => $model->id], [
						// 		'class' => 'btn btn-sm btn-white',
						// 		'data-pjax'=>0,'title'=>'Скачать XML файлы',
						// 		'onclick' => 'event.preventDefault(); var self = this; $.get($(this).attr("href"), function(response){ $.each(response.filesList, function(name, path){ setTimeout(function(){ console.log(name, "name"); $(self).parent().find("[data-role=\"downloader\"]").attr("href", path).attr("download", name); }, 1000); }); });'
						// 	]).'<a data-role="downloader" href="" download="" data-pjax="0"></a>'; 
						// },

						'lawyer' => function($model){
							
							return '<button type="button" data-id="'.$model->id.'" val="'.($model->is_lawyer ? '' : '1').'" data-e="is_lawyer" class="btn_is_lawyer btn '.($model->is_lawyer ? 'btn-warning' : 'btn-danger').'" title="'.($model->is_lawyer ? 'В работе' : '').'">'.($model->is_lawyer ? 'Юрист' : 'Юрист').'</button>';

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
				
				<hr>

				<div>

					<div class="card-box-row">
						<span class="column-title">Менеджер:</span>
						<span><?= ArrayHelper::getValue($model, 'user.name') ?></span>
					</div>	

					<div class="card-box-row">
						<span class="column-title">Табель</span>
						<span><?= \yii\helpers\ArrayHelper::getValue($model, 'user.role') ?></span>
					</div>							

				</div>				

				<hr>

				<div class="flex-box space-between">

					<div>

						<span class="column-title">Статус</span>
						
						<div class="card-box">
							
							<div class="pr-10">
								
								<?php
									if(Yii::$app->user->identity->getRoleName() != "Заказчик"){
									echo Html::dropDownList('status'.$model->id, $model->status, \app\models\Flight::statusLabels(), [
										'class' => 'form-control',
										'prompt' => 'Выберите',
										'onchange' => '$.get("/flight/update-attr?id='.$model->id.'&attr=status&value="+$(this).val());',
									]);
									} else {
									echo '<span>'.\yii\helpers\ArrayHelper::getValue(\app\models\Flight::statusLabels(), $model->status).'<span>';
									}
								?>				
							</div>
						
							<div>
								<?php //'<button type="button" data-id="'.$model->id.'" val="'.($model->is_signature ? '' : '1').'" data-e="is_signature" class="btn_is_signature btn '.($model->is_signature ? 'btn-primary' : 'btn-secondary').'">'.($model->is_signature ? 'Подписано' : 'Подписать').'</button>' ?>
							</div>

						</div>

						
					
					</div>

					
					<div>

						<span class="column-title">Процент дельты</span>
						
						<div class="card-box">
							<span><?= $model->delta_percent ?></span>
						</div>

					</div>

				</div>

<?php /*
				<hr>

				<div>
					<?php echo '<input style="margin-right: 5px;" type="checkbox" data-id="'.$model->id.'" 
								data-e="is_salary_payed" '.($model->is_salary_payed ? 'checked="true"' : '').'>'; ?>
						
					<span class="salary-label">З/П</span><span class="salary"><?=$model->salary ?></span>
				</div>
*/ ?>


				
			</div>


			<div class="card-column" style="">
				<div class="flex-box">
					<?php if(!$model->is_signature or \Yii::$app->user->identity->isSuperAdmin()): ?>
					<div>
						<label class="container_checkbox">
							<input type="checkbox" class="check_items" name="check_items" data-id="<?= $model->id ?>" data-e="check_items" value="<?=$model->id?>">
							<span class="checkmark"></span>
						</label>
					</div>
					<?php endif; ?>
					<div class="padding-0-5" style="margin-top: 6px;"><?=(isset($numeration[$model->id])? '№ '.$numeration[$model->id][0] : 'не указана Дата заявки'); ?></div>
					<div style="align-self: end; margin-left: auto;">	
						<span><?= \Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y H:i') ?></span>
					</div>

				</div>


				<hr>
				
				<div>
					<?php
					
					$output = '';
					$docs = explode(',', $role ? $role->docs : '');
		
					$json = json_decode($model->checks, true);
					$roleJson = [];
					foreach($roles as $role)
					{
					  foreach(explode(',', $role->docs) as $el)
					  {
						if($el == null){
						  continue;
						}
						if(in_array($el, $roleJson) == false){
						  $roleJson[] = $el;
						}
					  }
					}
		
		
					if(is_array($json) == false){
					  $json = [];
					}
		
					$hasDocs = [];
					foreach($roleJson as $doc){
					  $hasDocs[] = $doc;
					  $jsonValue = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.value", null);
					  $jsonDatetime = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.datetime", null);
					  if($jsonDatetime){
						$jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
					  }
					  $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;

					 //if(in_array($doc, $docs) == false){
					 //  $disabled = 'disabled=""';
					 //}

					  $output .= '<div class="card-box-row-2">';
					  $output .= '<span><label class="container_checkbox"><input '.$disabled.' type="checkbox" data-attr="checks" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'><span class="checkmark"></span></label> <span style="display: inline-block; margin-left: 35px;">'.$doc.'</span></span>';
					  $output .= '<span class="date"><i>'.$jsonDatetime.'</i></span>';
					  $output .= '</div>';
					}
					foreach($json as $jsonName => $jsonEl){
					  if(in_array($jsonName, $hasDocs)){
						continue;
					  }
					  $jsonValue = isset($jsonEl['value']) ? $jsonEl['value'] : null;
					  $jsonDatetime = isset($jsonEl['datetime']) ? $jsonEl['datetime'] : null;
					  if($jsonDatetime){
						$jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
					  }
					  $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;

					 //if(in_array($doc, $docs) == false){
					//   $disabled = 'disabled=""';
					// }

					  $output .= '<div class="card-box-row-2">';
					  $output .= '<span><label class="container_checkbox"><input '.$disabled.' type="checkbox" data-attr="checks" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'"><span class="checkmark"></span></label> <span style="display: inline-block; margin-left: 35px;">'.$jsonName.'</span></span>';
					  $output .= '<span class="date"><i>'.$jsonDatetime.'</i></span>';
					  $output .= '</div>';
					}
		
		
					echo $output;
					
					?>
				</div>
				<hr>
				<div>
					<?php
							
					$output = '';
					$docs = explode(',', $role ? $role->docs1 : '');

					$json = json_decode($model->checks1, true);
					$roleJson = [];
					foreach($roles as $role)
					{
					foreach(explode(',', $role->docs1) as $el)
					{
						if($el == null){
						continue;
						}
						if(in_array($el, $roleJson) == false){
						$roleJson[] = $el;
						}
					}
					}
					if(is_array($json) == false){
					$json = [];
					}

					$hasDocs = [];
					foreach($roleJson as $doc){
					$hasDocs[] = $doc;
					$jsonValue = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.value", null);
					$jsonDatetime = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.datetime", null);
					if($jsonDatetime){
						$jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
					}
					$disabled = !\Yii::$app->user->identity->can('flight_checks1') ? 'disabled=""' : null;
					// if(in_array($doc, $docs) == false){
					//   $disabled = 'disabled=""';
					// }
					$output .= '<div class="card-box-row-2">';
					$output .= '<span><label class="container_checkbox"><input '.$disabled.' type="checkbox" data-attr="checks1" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'><span class="checkmark"></span></label> <span style="display: inline-block; margin-left: 35px;">'.$doc.'</span></span>';
					$output .= '<span class="date"><i>'.$jsonDatetime.'</i></span>';
					$output .= '</div>';
					}
					foreach($json as $jsonName => $jsonEl){
					if(in_array($jsonName, $hasDocs)){
						continue;
					}
					$jsonValue = isset($jsonEl['value']) ? $jsonEl['value'] : null;
					$jsonDatetime = isset($jsonEl['datetime']) ? $jsonEl['datetime'] : null;
					if($jsonDatetime){
						$jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
					}
					$disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;
					// if(in_array($doc, $docs) == false){
					//   $disabled = 'disabled=""';
					// }
					$output .= '<div class="card-box-row-2">';
					$output .= '<span><label class="container_checkbox"><input '.$disabled.' type="checkbox" data-attr="checks1" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'><span class="checkmark"></span></label> <span style="display: inline-block; margin-left: 35px;">'.$jsonName.'</span></span>';
					$output .= '<span class="date"><i>'.$jsonDatetime.'</i></span>';
					$output .= '</div>';
					}

					echo $output;
							
					?>
				</div>
				<hr>
				<div>
					<?php
					
					$output = '';
					$docs = explode(',', $role ? $role->docs2 : '');
		
					$json = json_decode($model->checks2, true);
					$roleJson = [];
					foreach($roles as $role)
					{
					  foreach(explode(',', $role->docs2) as $el)
					  {
						if($el == null){
						  continue;
						}
						if(in_array($el, $roleJson) == false){
						  $roleJson[] = $el;
						}
					  }
					}
					if(is_array($json) == false){
					  $json = [];
					}
		
					$hasDocs = [];
					foreach($roleJson as $doc){
					  $hasDocs[] = $doc;
					  $jsonValue = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.value", null);
					  $jsonDatetime = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.datetime", null);
					  if($jsonDatetime){
						$jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
					  }
					  $disabled = !\Yii::$app->user->identity->can('flight_checks2') ? 'disabled=""' : null;
					  $output .= '<div class="card-box-row-2">';
					  $output .= '<span><label class="container_checkbox"><input '.$disabled.' type="checkbox" data-attr="checks2" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'"><span class="checkmark"></span></label> <span style="display: inline-block; margin-left: 35px;">'.$doc.'</span></span>';
					  $output .= '<span class="date"><i>'.$jsonDatetime.'</i></p>';
					  $output .= '</div>';
					}
					foreach($json as $jsonName => $jsonEl){
					  if(in_array($jsonName, $hasDocs)){
						continue;
					  }
					  $jsonValue = isset($jsonEl['value']) ? $jsonEl['value'] : null;
					  $jsonDatetime = isset($jsonEl['datetime']) ? $jsonEl['datetime'] : null;
					  if($jsonDatetime){
						$jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
					  }
					  $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;
					  $output .= '<div class="card-box-row-2">';
					  $output .= '<span><label class="container_checkbox"><input '.$disabled.' type="checkbox" data-attr="checks2" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'"><span class="checkmark"></span></label> <span style="display: inline-block; margin-left: 35px;">'.$jsonName.'</span></span>';
					  $output .= '<span class="date"><i>'.$jsonDatetime.'</i></p>';
					  $output .= '</div>';
					}
		
		
					echo $output;
					
					?>
				</div>
				<hr>
				<div>
					<?php
					
					$output = '';
					$docs = explode(',', $role ? $role->docs3 : '');
		
					$json = json_decode($model->checks3, true);
					$roleJson = [];
					foreach($roles as $role)
					{
					  foreach(explode(',', $role->docs3) as $el)
					  {
						if($el == null){
						  continue;
						}
						if(in_array($el, $roleJson) == false){
						  $roleJson[] = $el;
						}
					  }
					}
					if(is_array($json) == false){
					  $json = [];
					}
		
					$hasDocs = [];
					foreach($roleJson as $doc){
					  $hasDocs[] = $doc;
					  $jsonValue = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.value", null);
					  $jsonDatetime = \yii\helpers\ArrayHelper::getValue($json, "{$doc}.datetime", null);
					  if($jsonDatetime){
						$jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
					  }
					  $disabled = !\Yii::$app->user->identity->can('flight_checks3') ? 'disabled=""' : null;
					  $output .= '<div class="card-box-row-2">';
					  $output .= '<span><label class="container_checkbox"><input '.$disabled.' type="checkbox" data-attr="checks3" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'"><span class="checkmark"></span></label> <span style="display: inline-block; margin-left: 35px;">'.$doc.'</span></span>';
					  //$output .= '<span><input '.$disabled.' type="checkbox" data-attr="checks3" data-id="'.$model->id.'" data-multiple-e="'.$doc.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$doc.'</span>';
					  $output .= '<span style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></p>';
					  $output .= '</div>';
					}
					foreach($json as $jsonName => $jsonEl){
					  if(in_array($jsonName, $hasDocs)){
						continue;
					  }
					  $jsonValue = isset($jsonEl['value']) ? $jsonEl['value'] : null;
					  $jsonDatetime = isset($jsonEl['datetime']) ? $jsonEl['datetime'] : null;
					  if($jsonDatetime){
						$jsonDatetime = \Yii::$app->formatter->asDate($jsonDatetime, 'php:d.m.Y H:i');
					  }
					  $disabled = !\Yii::$app->user->identity->can('flight_checks') ? 'disabled=""' : null;
					  $output .= '<div class="card-box-row-2">';
					  $output .= '<span><label class="container_checkbox"><input '.$disabled.' type="checkbox" data-attr="checks3" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'"><span class="checkmark"></span></label> <span style="display: inline-block; margin-left: 35px;">'.$jsonName.'</span></span>';
					  //$output .= '<span><input '.$disabled.' type="checkbox" data-attr="checks3" data-id="'.$model->id.'" data-multiple-e="'.$jsonName.'" '.($jsonValue ? 'checked="true"' : '').'"> '.$jsonName.'</span>';
					  $output .= '<span style="font-size: 9px; margin-top: 0;"><i>'.$jsonDatetime.'</i></span>';
					  $output .= '</div>';
					}
		
		
					echo $output;
					
					?>
				</div>
		
			</div>

			<div class="card-column ss <?= ((strtotime($model->date_cr) < strtotime(date('Y-m-d')) or $model->is_signature ) ? 'c-green' : '') ?>">

				<div class="flex-box jc-sb">

					<div class="card-box-column-3">
						<span class="column-header">Заявка</span>
					</div>
					<div class="card-box-column-3 padding-0-5">
						<span>№<?= $model->order ?></span>
					</div>
					<div class="card-box-column-3">
						<?= \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') ?>
					</div>
				</div>

				<hr>	

				<div class="flex-box space-between">


					<div>
						<span>ИНН: <?= ArrayHelper::getValue($model, 'organization.inn') ?></span>
					</div>
					<div>
						<span>Организация: <?= ArrayHelper::getValue($model, 'organization.name') ?></span>
					</div>

				</div>
				
				<hr>

				<div>	
					<div>
						<span><?= $model->rout ?></span><br>
						<span class="label label-accent"><?= $model->distance ?> км</span>
					</div>
					<?php /*
					<div>
						<span>Погрузка</span><br>
						<span> <?= \Yii::$app->formatter->asDate($model->shipping_date, 'php:d.m.Y') </span>

					</div>
					*/
					?>
				</div>

<?php /*
				<hr>

				<div class="card-box-row-2">



					<div class="card-box-row">
						<span class="column-title">Зарплата:</span>
						<span><?= \Yii::$app->formatter->asDecimal($model->salary) ?></span>	
					</div>
				
				</div>

*/ ?>
				<hr>

				<div class="flex-box">
                                    
                                    <?php $disabled = !\Yii::$app->user->identity->can('flight_check_salary') ? 'disabled=""' : null; ?>
                                    
					<div class="is_salary_payed">
						<?php echo '<label class="container_checkbox"><input '.$disabled.' style="margin-right: 5px;" type="checkbox" data-id="'.$model->id.'" 
									data-e="is_salary_payed" '.($model->is_salary_payed ? 'checked="true"' : '').'><span class="checkmark"></span></label>'; ?>
					</div>		
					<div>
						<span class="salary-label" style="">З/П</span><span class="salary"><?=$model->salary ?></span>
					</div>
					<div class="is_salary_payed_datetime">
						<?php if($model->is_salary_payed_datetime): ?>
							<?= \Yii::$app->formatter->asDate($model->is_salary_payed_datetime, 'php:d.m.Y') ?>
						<?php endif; ?>
					</div>
				</div>


				<hr>
				
					<div class="flex-box">
						<div>
                                                        <?php $disabled = !\Yii::$app->user->identity->can('flight_check_salary') ? 'disabled=""' : null; ?>
                                                    
							<?php
									echo '<label class="container_checkbox">';
										echo '<input '.$disabled.' class="is_recoil_payment" type="checkbox" data-id="'.$model->id.'" data-e="is_recoil_payment" '.($model->is_recoil_payment ? 'checked="true"' : '').'">';
										echo '<span class="checkmark"></span>';
									echo '</label>';
							?>

							


						</div>

						
							<div>Баллы:</div>

							<div class="recoil">
								<?=$model->recoil ?>
							</div>

							<div class="is_recoil_payment_date">
								<?php if($model->is_recoil_payment_date): ?>	
									<?= \Yii::$app->formatter->asDate($model->is_recoil_payment_date, 'php:d.m.Y') ?>
								<?php endif; ?>
							</div>
						
					</div>

			</div>

			<div class="card-column сustomer <?= ($model->is_signature ? 'c-green' : '') ?>">


				<div>

					<div class="flex-box space-between">

						<div>
							<span class="column-header">Заказчик</span>
						</div>

						<div>
						<?php 
						
						//echo Flight::find()->andWhere(['zakazchik_id' => $model->zakazchik_id])->count();  
						
						$count = \Yii::$app->myCache->get('count_zakazchik_id_'.$model->zakazchik_id);
						if($count == null){
							$count = Flight::find()->andWhere(['zakazchik_id' => $model->zakazchik_id])->count();
							\Yii::$app->myCache->set('count_zakazchik_id_'.$model->zakazchik_id, $count);
						}
					
						//echo $count;

						/*

						$sql = Flight::find()->select('count(*)')
						->andWhere(['zakazchik_id' => $model->zakazchik_id])
						->createCommand()
						->getRawSql();

						$count = \Yii::$app->myCache->get($sql);
						if($count == null){
							$count = Flight::find()->andWhere(['zakazchik_id' => $model->zakazchik_id])->count();
							\Yii::$app->myCache->set($sql, $count);
						}
					
						echo $count;	
						
						*/
                                                
                                                //$count = 29;
                                                $num_class = '';
                                                if($count>=30){
                                                    $num_class="color-green";
                                                }elseif($count>=20){
                                                    $num_class="color-yellow";
                                                }
						?>
                                                <span class="num-shipment <?=$num_class?>"><?=$count?></span>
						</div>

					</div>

					<div class="flex-box space-between">

						<div>
							<span>УПД: <?= $model->upd ?></span><br>
						</div>
						<div>
							<?php if($model->date_cr): ?>	
								<span><?= \Yii::$app->formatter->asDate($model->date_cr, 'php:d.m.Y') ?></span>
							<?php endif; ?>
						</div>

						

					</div>

				</div>
				
				<hr>

				<?php if(ArrayHelper::getValue($model, 'zakazchik.name') or ArrayHelper::getValue($model, 'zakazchik.inn')): ?>
				<div class="flex-box space-between">
						<div><?= ArrayHelper::getValue($model, 'zakazchik.name') ?></div>
						<div><?= ArrayHelper::getValue($model, 'zakazchik.inn') ?></div>
				</div>	
				<hr>
				<?php endif; ?>

				

				<div>				
					
					<div class="pr5">
                                            
                                            
                                            <?php 
                                                

                                                if($model->date2){
                                                    $holidays = \app\models\Holiday::find()
                                                            ->where(['between','date', $model->date2,date('Y-m-d')])
                                                            ->count();
                                                    
                                                    $d = $holidays;
                                                    
                                                    if($model->col2 && is_numeric($model->col2)) $d += + $model->col2; 
                                                    
                                                    //echo '-'.$d; die;
                                                
                                                    //дата письмо заказчика + Кол-во дней
                                                     $date = date('Y-m-d', strtotime($model->date2 . ' +'.$d.' day')); 
                                                     
                                                }
                                                //echo $holidays;
                                                
                                                
                                                $class_payed = '';
                                                
                                                if($model->is_payed):
                                                    //$class_payed = 'green';                                                
                                                elseif($model->date2 && $model->col2): 	
                                                    $origin = new DateTimeImmutable($date);
                                                    $target = new DateTimeImmutable(date('Y-m-d'));
                                                    $interval = $origin->diff($target);
                                                    $days = $interval->format('%r%a');
                                                    //echo $days;
                                                    if($days<=1){
                                                        $class_payed = 'red';
                                                    }elseif($days<=3){
                                                        $class_payed = 'yellow';
                                                    } 
                                                endif; 
                                                
                                            ?>  
                                            
                                                <div class="payed <?=$class_payed?>">
                                                    <div class="flex-box">


                                                            <div>
                                                            <?php $disabled = !\Yii::$app->user->identity->can('flight_check_salary') ? 'disabled=""' : null; ?>    
                                                            <?php
                                                                    //if(Yii::$app->user->identity->can('flight_payment_check')){
                                                                            echo '<label class="container_checkbox">';
                                                                                    echo '<input '.$disabled.' class="is_payed" type="checkbox" data-id="'.$model->id.'" data-e="is_payed" '.($model->is_payed ? 'checked="true"' : '').'">';
                                                                                    echo '<span class="checkmark"></span>';
                                                                            echo '</label>';
                                                                    //}
                                                            ?>


                                                            </div>

                                                            <div class="asc mh-32px mr-6px">Оплата</div>


                                                            <div>
                                                                    <input type='input' data-id='<?=$model->id?>' data-e='we' class='form-control we show-prompt' value='<?=$model->we?>' style="display: none;">
                                                                    <span><?=$model->we?></span>
                                                                    <span><?=$model->pay_us?></span>								
                                                            </div>

                                                    </div>

                                                    <div class="flex-box text-center jc-sb">
                                                        <div>
                                                            <?php if($model->is_payed_date): ?>	
                                                                    <?= \Yii::$app->formatter->asDate($model->is_payed_date, 'php:d.m.Y') ?>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div>
                                                            <?php if($model->date2 && $model->col2): ?>	
                                                                    <?= \Yii::$app->formatter->asDate($date, 'php:d.m.Y') ?>
                                                            <?php endif; ?>                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
						<div class="flex-box">

							<div>	
                                                        <?php $disabled = !\Yii::$app->user->identity->can('flight_check_salary') ? 'disabled=""' : null; ?>  
							<?php
									echo '<label class="container_checkbox">';
										echo '<input '.$disabled.' class="is_we_prepayment" type="checkbox" data-id="'.$model->id.'" data-e="is_we_prepayment" '.($model->is_we_prepayment ? 'checked="true"' : '').'">';
										echo '<span class="checkmark"></span>';
									echo '</label>';
							?>
							</div>


							<div class="asc mh-32px mr-6px">Аванс</div>
							<div class="we_prepayment_form">
								<input type='input' data-id='<?=$model->id?>' data-e='we_prepayment_form' class='form-control we_prepayment_form show-prompt' value='<?=$model->we_prepayment_form?>' placeholder='Предоплата' style="display: none;">
								<span><?=$model->we_prepayment_form?></span>
                                                                <span><?=$model->pay_us?></span>
							</div>
						</div>

						<div>
							<?php if($model->date_we_prepayment): ?>	
								<?= \Yii::$app->formatter->asDate($model->date_we_prepayment, 'php:d.m.Y') ?>
							<?php endif; ?>
						</div>
					
					</div>

					<div class="flex-box jc-end">
					
						<div>
							<div>
								<input type='input' data-id='<?=$model->id?>' data-e='we_prepayment' class='form-control we_prepayment show-prompt' value='<?=$model->we_prepayment?>' placeholder='Предоплата' style="display: none;">
								<?=$model->we_prepayment?>
							</div>
						</div>



					</div>

				</div>

				<hr>
				



			</div>
			<div class="card-column carrier <?= ($model->is_signature ? 'c-green' : '') ?>">
				
				<div>
					<div class="flex-box space-between">
						<div>
							<span class="column-header">Перевозчик</span>
						</div>
						<div><?php 
						
						//echo Flight::find()->andWhere(['carrier_id' => $model->carrier_id])->count();		
						
						//echo 
								
						//die;

						
						$count = \Yii::$app->myCache->get('count_carrier_id_'.$model->carrier_id);
						if($count == null){
							$count = Flight::find()->andWhere(['carrier_id' => $model->carrier_id])->count();
							\Yii::$app->myCache->set('count_carrier_id_'.$model->carrier_id, $count);
						}
					
						//echo $count;
						
						/*
						$sql = Flight::find()->select('count(*)')
						->andWhere(['carrier_id' => $model->carrier_id])
						->createCommand()
						->getRawSql();

						$count = \Yii::$app->myCache->get($sql);
						if($count == null){
							$count = Flight::find()->andWhere(['carrier_id' => $model->carrier_id])->count();
							\Yii::$app->myCache->set($sql, $count);
						}

						echo $count;
						*/
					//die;
                                                //$count = 29;
                                                $num_class = '';
                                                if($count>=30){
                                                    $num_class="color-green";
                                                }elseif($count>=20){
                                                    $num_class="color-yellow";
                                                }                                                
						
						?>
                                                    <span class="num-shipment <?=$num_class?>"><?=$count?></span></div>
					</div>

					<div class="flex-box space-between">	
						<div>
							<span>Акт: <?= $model->act ?></span><br>
						</div>
						<div>
							<?php if($model->act_date): ?>
								<span><?= \Yii::$app->formatter->asDate($model->act_date, 'php:d.m.Y') ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<hr>
				<div class="flex-box space-between">
					<div><?= \yii\helpers\ArrayHelper::getValue($model, 'carrier.name') ?></div>
					<div><?= ArrayHelper::getValue($model, 'carrier.inn') ?></div>
				</div>
				<hr>



				<div>	
                                    
                                    <div class="pr5">
					
                                <?php 
                                
                                    if($model->date3){

                                    $holidays = \app\models\Holiday::find()
                                            ->where(['between','date', $model->date3,date('Y-m-d')])
                                            ->count();  
                                    
                                    $d = $holidays;

                                    if($model->col1 && is_numeric($model->col1)) $d += $model->col1;                                    
                                    
                                    //дата письмо перевозчика + Кол-во дней
                                    $date = date('Y-m-d', strtotime($model->date3 . ' +'.$d.' day')); 
                                    
                                    }

                                    $class_payed = '';

                                    if($model->is_driver_payed):
                                        //$class_payed = 'green';                                                
                                    elseif($model->date3 && $model->col1): 	
                                        $origin = new DateTimeImmutable($date);
                                        $target = new DateTimeImmutable(date('Y-m-d'));
                                        $interval = $origin->diff($target);
                                        $days = $interval->format('%r%a');
                                        if($days<=1){
                                            $class_payed = 'red';
                                        }elseif($days<=3){
                                            $class_payed = 'yellow';
                                        } 
                                    endif; 

                                ?>  

                                            <div class="payed <?=$class_payed?>">					

						<div class="flex-box">

                                                        <?php $disabled = !\Yii::$app->user->identity->can('flight_check_salary') ? 'disabled=""' : null; ?> 
							<?php
								//if(Yii::$app->user->identity->can('flight_payment_check')){

									echo '<label class="container_checkbox">';
										echo '<input '.$disabled.' class="is_driver_payed" type="checkbox" data-id="'.$model->id.'" data-e="is_driver_payed" '.($model->is_driver_payed ? 'checked="true"' : '').'">';
										echo '<span class="checkmark"></span>';
									echo '</label>';

								//}
							?>

							<div class="asc mh-32px mr-6px">Оплата</div>


							<div>
								<input type='input' data-id='<?=$model->id?>' data-e='payment_out' class='form-control payment_out show-prompt' value='<?=$model->payment_out?>' style="display: none;">
								<span><?=$model->payment_out?></span>
								<span><?=$model->otherwise2?></span>								
							</div>

						</div>

						<div class="flex-box text-center jc-sb">
                                                        <div>
                                                            <?php if($model->is_driver_payed_date): ?>
                                                            <?= \Yii::$app->formatter->asDate($model->is_driver_payed_date, 'php:d.m.Y') ?>	
                                                            <?php endif; ?>	
                                                        </div>
                                                        <div>
                                                            <?php if($model->date3 && $model->col1): ?>	
                                                                    <?= \Yii::$app->formatter->asDate($date, 'php:d.m.Y') ?>
                                                            <?php endif; ?>                                                        
                                                        </div>                                                    
						</div>
                                                </div>
                                                <hr>
						<div class="flex-box">
							<div>
								<?php $disabled = !\Yii::$app->user->identity->can('flight_check_salary') ? 'disabled=""' : null; ?> 
                                                                <?php
									echo '<label class="container_checkbox">';
										echo '<input '.$disabled.' class="is_payment_out_prepayment" type="checkbox" data-id="'.$model->id.'" data-e="is_payment_out_prepayment" '.($model->is_payment_out_prepayment ? 'checked="true"' : '').'">';
										echo '<span class="checkmark"></span>';
									echo '</label>';
								?>
							</div>
							<div class="asc mh-32px mr-6px">Аванс</div>
							<div class="payment_out_prepayment_form">
								<input type='input' data-id='<?=$model->id?>' data-e='payment_out_prepayment_form' class='form-control payment_out_prepayment_form show-prompt' value='<?=$model->payment_out_prepayment_form?>' placeholder='Предоплата' style="display: none;">
								<span><?=$model->payment_out_prepayment_form?></span>
                                                                <span><?=$model->otherwise2?></span>
							</div>
						</div>
						<div>
							<?php if($model->date_payment_out_prepayment): ?>
								<?= \Yii::$app->formatter->asDate($model->date_payment_out_prepayment, 'php:d.m.Y') ?>		
							<?php endif; ?>	
						</div>
					</div>
                                        
					<div class="flex-box jc-end">
					
						<div>
							<div>
								<input type='input' data-id='<?=$model->id?>' data-e='payment_out_prepayment' class='form-control payment_out_prepayment show-prompt' value='<?=$model->payment_out_prepayment?>' style="display: none;" placeholder='Предоплата'>
								<?=$model->payment_out_prepayment?>
							</div>
						</div>



					</div>
                                        
                                        </div>

				<hr>






			</div>

			<div class="card-column driver" style="">
				<div class="flex-box space-between">
					<div>
						<span class="column-header">Водитель</span>
					</div>
					<div>
						<?php
							//$driverCount = Flight::find()->andWhere(['zakazchik_id' => $model->zakazchik_id, 'auto' => $model->auto, 'driver_id' => $model->driver_id, 'carrier_id' => $model->carrier_id])->count();

							/*
							$sql = Flight::find()->select('count(*)')
							->andWhere(['zakazchik_id' => $model->zakazchik_id, 'auto' => $model->auto, 'driver_id' => $model->driver_id, 'carrier_id' => $model->carrier_id])
							->createCommand()
							->getRawSql();
	
							$driverCount = \Yii::$app->myCache->get($sql);
							if($driverCount == null){
								$driverCount = Flight::find()->andWhere(['zakazchik_id' => $model->zakazchik_id, 'auto' => $model->auto, 'driver_id' => $model->driver_id, 'carrier_id' => $model->carrier_id])->count();
								\Yii::$app->myCache->set($sql, $driverCount);
							}
						
							echo $driverCount;
							*/

							$key_name = 'driverCount_'.$model->zakazchik_id.'_'.$model->auto.'_'.$model->driver_id.'_'.$model->carrier_id;
							$count = \Yii::$app->myCache->get($key_name);
							if($count == null){
								$count = Flight::find()->andWhere(['zakazchik_id' => $model->zakazchik_id, 'auto' => $model->auto, 'driver_id' => $model->driver_id, 'carrier_id' => $model->carrier_id])->count();
								\Yii::$app->myCache->set($key_name, $count);
							}
						
							//echo $driverCount;

                                                        /*
							if($driverCount > 100){
								$numClass = 'num-shipment color-purple';
							} elseif($driverCount > 50) {
								$numClass = 'num-shipment color-green';
							} else {
								$numClass = 'num-shipment';
							}
                                                         
                                                         */
                                                        //$count = 30;
                                                        $num_class = '';
                                                        if($count>=30){
                                                            $num_class="color-green";
                                                        }elseif($count>=20){
                                                            $num_class="color-yellow";
                                                        }                                                          
                                                        
						?>
						<span class="num-shipment <?= $num_class ?>"><?php echo $count; ?></span>
					</div>
				</div>

			

				<div class="row">
					<div class="col-md-12" style="margin-bottom: 10px; margin-top: 10px;">
						<span class="column-title">ФИО</span>

						<span><?= \yii\helpers\ArrayHelper::getValue($model, 'driver.data') ?></span>

						<?php /*
							echo Select2::widget([
								'name' => 'driver_id',
								'data' => $driverData,
								'value' => $model->driver_id,
								'pluginOptions' => [
									'allowClear' => true
								],
								'pluginEvents' => [
									'change' => 'function(){ 
									

										$.get("/flight/update-ajax?id='.$model->id.'&attribute=driver_id&value="+$(this).val(), function(response){
											console.log(response, "response");

											$.pjax.reload({
												container: "#crud-datatable-flight-pjax",
												url: "/flight",
												timeout: 0,
											});											
										});		
									}
									'
								],
							]);
							*/
						?>
						<hr>

						<span class="column-title">Номер автомобиля</span>
						<span><?= \yii\helpers\ArrayHelper::getValue($model, 'driver.data_avto') ?> <?= \yii\helpers\ArrayHelper::getValue($model, 'driver.car_number') ?> <?= \yii\helpers\ArrayHelper::getValue($model, 'driver.car_truck_number') ?></span>
						
						<hr>

						<span class="column-title">Номер прицепа</span>
						<span><?= \yii\helpers\ArrayHelper::getValue($model, 'driver.car_truck_number') ?></span>
						
						<hr>

						<span class="column-title">Телефон</span>
						<span><?= \yii\helpers\ArrayHelper::getValue($model, 'driver.phone') ?></span>
					</div>



				</div>

			</div>


			<div class="card-column" style="">
				<span class="column-header">Груз</span>

				
				<span class="column-title"><?= $model->getAttributeLabel('view_auto') ?></span>
				<span>
					<?php /*
					<input type='input' data-id='<?=$model->id?>' data-e='view_auto' class='form-control view_auto show-prompt' 
					value='<?=$model->view_auto?>'>
					*/ ?>
					<?=$model->view_auto?>
				</span>

				<hr>

				<span class="column-title"><?= $model->getAttributeLabel('name') ?></span>
				<span>
					<?php /*
					<input type='input' data-id='<?=$model->id?>' data-e='name' class='form-control name show-prompt' 
					value='<?=$model->name?>'>
					*/ ?>
					<?=$model->name?>
				</span>

				<hr>
				
				<div class="row">
					<div class="col-md-4">

						<span class="column-title"><?= $model->getAttributeLabel('cargo_weight') ?></span>
						<span>
							<?=$model->cargo_weight?>
						</span>

					</div>
					<div class="col-md-8">

						<span class="column-title"><?= $model->getAttributeLabel('shipping_date') ?></span>
						<span>
							<?php if($model->shipping_date): ?>
								<?= \Yii::$app->formatter->asDate($model->shipping_date, 'php:d.m.Y') ?>
							<?php endif; ?>	
						</span>


					</div>
				</div>


				


				<hr>

				<div class="card-box-row-2">
					<div class="mr10">Страховка:</div>
					<div>
					<?php
						if($model->is_insurance){
							echo $model->ensurance_prime;
						} else {
							echo round($model->we * 0.002);
						}
					?>
					</div>
				</div>

				<?php if($model->is_ensurance_payment): ?>
				<div class="card-box-row-2">
					<div class="mr10">Сумма страховки:</div>
					<div><?php echo ($model->id*0.002) ?></div>
				</div>
				<?php endif; ?>


				<div class="card-box-row-2">
					<div class="mr10">Дап. расходы:</div>
					<div><?= $model->additional_credit ?></div>
				</div>


			</div>


			<div class="card-column card-column-email" style="">
				<span class="column-header">Почта</span>
                                
                                <?php $disabled = !\Yii::$app->user->identity->can('mail_update') ? 'disabled=""' : null; ?> 
				
				<div class="flex-box">

					<div>
				
						<span class="column-title"><?= $model->getAttributeLabel('track_number') ?></span>
						<span>
							<input <?=$disabled?> type='input' data-id='<?=$model->id?>' data-e='track_number' class='track_number form-control show-prompt' 
							value='<?=$model->track_number?>' placeholder='Трек номер'>
						</span>

					</div>
					
					<div>

						<span class="column-title"><?= $model->getAttributeLabel('date2') ?></span>
						<span>
							<input <?=$disabled?> type='input' data-id='<?=$model->id?>' data-e='date2' class='date2 form-control' 
							value='<?php if($model->date2) echo date('d.m.Y',strtotime($model->date2))?>' placeholder='Дата Письмо Заказчику'>
						</span>

					</div>
				
				</div>

				<hr>

				<div class="flex-box">

					<div>
						<span class="column-title"><?= $model->getAttributeLabel('track_number_driver') ?></span>
						<span>
							<input <?=$disabled?> type='input' data-id='<?=$model->id?>' data-e='track_number_driver' class='track_number_driver form-control show-prompt' 
							value='<?=$model->track_number_driver?>' placeholder='Трек номер водителя'>
						</span>
					</div>	

					<div>
						<span class="column-title"><?= $model->getAttributeLabel('date3_') ?></span>
						<span>
							<input <?=$disabled?> type='input' data-id='<?=$model->id?>' data-e='date3' class='date3 form-control' 
							value='<?php if($model->date3) echo date('d.m.Y',strtotime($model->date3))?>' placeholder='Трек номер водителя'>
						</span>
					</div>

				</div>
				<hr>



			</div>
			
			
		</div>
<?php 

$dateCr = new \DateTime($model->date_cr);
$dateCr->modify('+2 days');

$managerReadonly = (\Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->can('flight_btn_update')) && $model->is_signature && $dateCr->format('Y-m-d') <= date('Y-m-d');

$managerReadonlyDateTime = $managerReadonly && $dateCr->format('Y-m-d') <= date('Y-m-d');

if($managerReadonlyDateTime){
$script = <<< JS

//$("#card-{$model->id} input").attr('disabled', true);
//$("#card-{$model->id} select").attr('disabled', true);

JS;

$this->registerJs($script, \yii\web\View::POS_READY);
}

?>



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

$( function() {
    $( ".shipping_date, .date2, .date3,#flightsearch-salary_from,#flightsearch-salary_to,#salary_from,#salary_to" ).datepicker({ dateFormat: 'dd.mm.yy' });

	$('input.show-prompt').on( "click", function( event ) {
		let text = prompt('Введите значение',$(this).val());
		if (text === null) {
        	return;
    	}
		$(this).val(text);
		$(this).change();
	}).on( "mousemove", function( event ) {
		$(this).css({"border-color":"green","background-color":"white"});
	}).on( "mouseleave", function( event ) {
		$(this).css({"border-color":"#ccd0d4","background-color":"transparent"});
	})

	$('#salary_filter').on( "click", function( event ) {
		$('.box-salary').toggleClass( "active" );
    	});
        

        $('#salary_from').on( "change", function( event ) {
           var value = $(this).val();
            $('#flightsearch-salary_from').val(value);
        });

        $('#salary_to').on( "change", function( event ) {
           var value = $(this).val();
            $('#flightsearch-salary_to').val(value);
        });  
       
});
$('#delete_checks').click(function(){

	if (window.confirm("Вы действительно хотите удалить выбранные записи?")) {
		var pks = $(this).attr('pks');

		$.post('/flight/bulk-delete', { pks: pks }, function(response){
			console.log(response, 'response');
			$.pjax.reload({container: "#crud-datatable-flight-pjax"});
		});	

	}
});

$('.ZipXmlDomnload').click(function(){
	var id = $(this).data('id');
	$.get('/flight/zip-xml-download?id='+id, function(response){
		$.each(response.filesList, function(key, val){
			console.log(key + ': ' + val);
			const link = document.createElement('a');
			link.setAttribute('href', '../'+val);
			link.setAttribute('download', key);
			link.click();
		})
		return false;
	});
});
        
function update_pks(){

	var ids = [];
	$('.check_items:checkbox:checked').each(function () {
		ids.push($(this).val());
	});

	$('#delete_checks').attr('pks',ids.join(','));

}


$('.check_items').change(function(){
	update_pks();
});



$('#check_all').change(function(){

	//var value = $(this).is(':checked') ? 1 : 0;	

	if($(this).is(':checked')){
		$(".check_items").prop( "checked", true );
	}else{
		$(".check_items").prop( "checked", false );
	}

	update_pks();

});


$('[data-edit="is_payed"]').change(function(){

console.log('here');

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

$.get('/flight/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
    console.log(response, 'response');
});

});



$('.btn_is_lawyer').click(function(){

	var btn = $(this);
	var attr = btn.data('e');
	var id = btn.data('id');
	var value = btn.attr('val');

    $.get('/flight/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
        //console.log(response, 'response');
		$.pjax.reload({
			container: "#crud-datatable-flight-pjax",
			//url: '/flight',
			timeout: 0,
    	});
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

    $.get('/flight/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
        //console.log(response, 'response');
		$.pjax.reload({
			container: "#crud-datatable-flight-pjax",
			//url: '/flight',
			timeout: 0,
    	});
    });

});

$('[data-multiple-e]').change(function(){

    var key = $(this).data('multiple-e');
    var attr = $(this).data('attr');
    var id = $(this).data('id');
    var value = $(this).is(':checked');

    $.get('/flight/update-checks?id='+id+'&attr='+attr+'&key='+key+'&value='+value, function(response){
        // console.log(response, 'response');

		$.pjax.reload({
			container: "#crud-datatable-flight-pjax",
			//url: '/flight',
			timeout: 0,
    	});

    });

});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>

<?php \yii\widgets\Pjax::end() ?>

<?php

$checkPks = [];

$session = \Yii::$app->session;

if($session->has('check-flight-session')){
    $checkPks = $session->get('check-flight-session');
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
        window.location = '/flight/view?id='+id;
    }
});

$(document).on('pjax:complete' , function(event) {
    $('[data-key]').dblclick(function(e){
        if($(e.target).is('td')){
            var id = $(this).data('key');
            window.location = '/flight/view?id='+id;
        }
    });
});

function getChecked(){
    var checked = [];
    $('.kv-row-select input:checked').each(function(){
        checked.push($(this).val());
    });

    $.get('/flight/check-session?pks='+checked.join(','), function(response){

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
