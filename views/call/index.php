<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Звонки';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['call/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['call/create'];
}

$clientPayment = isset($_GET['client_payment']) ? boolval($_GET['client_payment']) : false;
$driverPayment = isset($_GET['driver_payment']) ? boolval($_GET['driver_payment']) : false;

$clientPaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['call/index', 'client_payment' => ($clientPayment ? 0 : 1)]);
$driverPaymentUrl = ArrayHelper::merge(\Yii::$app->request->queryParams, ['call/index', 'driver_payment' => ($driverPayment ? 0 : 1)]);


if(true){
    // $exportButtons = Html::a(Yii::t('app', 'Экспорт').' <i class="fa fa-file-excel-o"></i>', ArrayHelper::merge(['call/export'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт (второй вариант)').' <i class="fa fa-file-excel-o"></i>', ArrayHelper::merge(['call/export2'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт (третий вариант)'), ArrayHelper::merge(['call/export3'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]).' '.Html::a(Yii::t('app', 'Экспорт 4'), ['call/export4'], ['class' => 'btn btn-warning', 'role' => 'modal-remote']);
    $exportButtons = Html::a(Yii::t('app', 'Импорт').' <i class="fa fa-file-excel-o"></i>', ArrayHelper::merge(['call/add'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'role' => 'modal-remote']);
} else {
    $exportButtons = '';
}

?>

<style>
    .modal-dialog {
        width: 80% !important;
    }
</style>

<style>


    #ajaxCrudDatatable .panel-info>.panel-heading {
        display: none!important;
    }
    #ajaxCrudDatatable .panel-info>.kv-panel-before>.pull-right {
        float: left!important;
    }
    #ajaxCrudDatatable .panel-info>.table-responsive {
        padding:  0px !important;
    }

    [data-payed='1'] td {
        background: #cee !important;
        border-color: #99dede !important;
    }

    [data-unpayed='1'] td {
        background: #ffdedd !important;
        border-color: #ffbdbc !important;
    }

    td.success {
        background: #a5efef !important;
        border-color: #6fd1d1 !important;
    }

    td.danger {
        background: #ff9d9a !important;
        border-color: #f57674 !important;
    }

    #crud-datatable-call #crud-datatable-flight-container {
        height: 70vh !important;
    }

    .kv-grid-container {
        height: 70vh !important;
    }

    table th, table td {
        padding: 2px 4px !important;
    }

    .panel-danger .panel-heading {
        display: none !important;
    }

    .btn-toolbar.kv-grid-toolbar {
        display: none;
    } 

    .table-condensed thead tr th {
        padding: 1px 1px !important;
    }

    .table {
        font-size: 12px !important;    
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

<?php  \yii\widgets\Pjax::begin(['id' => 'crud-datatable-flight-pjax']) ?>

<?php \yii\widgets\Pjax::end() ?>


</div>


<div class="col-md-12">
	<div class="card card-shadow m-b-10">
		<div class="row">
			<?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['call/index']]) ?>

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

                <?= $form->field($searchModel, 'inn', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	

                <?= $form->field($searchModel, 'site', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	

                <?= $form->field($searchModel, 'industry', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	

                <?= $form->field($searchModel, 'city', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [

                'data' => \yii\helpers\ArrayHelper::map(\app\models\Call::find()->all(), 'city', 'city'),
                'options' => ['prompt' => 'Выбрать', 'multiple' => false],
                    'pluginOptions' => [
                            'allowClear' => true,
                            'tags' => false,
                            'tokenSeparators' => [','],
                    ]
                ]) ?>

                </div>
                            
                            <div class="col-md-12">

                <?= $form->field($searchModel, 'timezone', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	


                <?= $form->field($searchModel, 'result', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	

                <?= $form->field($searchModel, 'post', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	


                <?= $form->field($searchModel, 'client_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [

                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Client::find()->andWhere(['id' => $searchModel->client_id])->all(), 'id', 'name'),
                    'options' => ['prompt' => 'Выбрать', 'multiple' => false],
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


                <?= $form->field($searchModel, 'phone', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput() ?>	

                <?= $form->field($searchModel, 'create_at', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->widget(\kartik\daterange\DateRangePicker::class, [
                                'convertFormat'=>true,
                                'pluginEvents' => [
                                    'cancel.daterangepicker'=>'function(ev, picker) {}'
                                ],
                                'pluginOptions' => [
                                    'singleDatePicker' => true,
                                    'opens'=>'right',
                                    'locale' => [
                                        'cancelLabel' => 'Clear',
                                        'format' => 'Y-m-d',
                                    ]
                                ]
                                ]) ?>


				</div>				


				<div class="col-md-12">
					<hr style="margin-top: 5px; margin-bottom: 15px;">
				</div>

				<div class="col-md-12">
					<div style="float: right;">
						<?= Html::a('Сбросить', ['call/index'], ['class' => 'btn btn-white']) ?>
						<?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
					</div>
				</div>

			<?php ActiveForm::end() ?>
		</div>
	</div>
</div>    


</div>



<div class="call-index">
    <div id="ajaxCrudDatatable">


    <?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-call',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 6,
    'serialAttribute' => 'id',
    'titleAttribute' => 'id',
    'listOptions' => [
        'style' => 'height: 468px; overflow-y: scroll;',
    ],
    'noEmpty' => true,
    'list' => [
        //'id',
        //'phone',
        [
            'attribute' => 'phone',
            'label' => 'Телефон и контакт',
            'content' => function($model){
                return $model->phone;
            },
        ],        
        'phone1',
        'phone2',
        'client_id',
        [
            'attribute' => 'status',
            'label' => 'Статус',
            'content' => function($model){
                $statusLabels = \app\models\Call::statusLabels();
                if($model->status) return $statusLabels[$model->status];
            },
        ],
        'inn',
        'post',
        'site',
        'industry',
        'region',
        'city',
        'contact_name',
        'timezone',
        'result',
        'result_text',
        'files',
        'user_id',
        'create_at',
    ],
    'buttonsTemplate' => '{view} {update} {delete}',
    'buttons' => [
        'view' => function($model){
            return Html::a('<i class="fa fa-eye"></i>', ['call/view', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Быстрый просмотр', 'class' => 'btn btn-sm btn-white']);
        },
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['call/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['call/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
                            'class' => 'btn btn-sm btn-white', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию']);
        },
    ],
]) ?>
        
<?php
$script = <<< JS
$('[data-key]').dblclick(function(e){
    if($(e.target).is('td')){
        var id = $(this).data('key');
        window.location = '/call/view?id='+id;
    }
});

$(document).on('pjax:complete' , function(event) {
    $('[data-key]').dblclick(function(e){
        if($(e.target).is('td')){
            var id = $(this).data('key');
            window.location = '/call/view?id='+id;
        }
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?>

    </div>
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'clientOptions' => [
        'backdrop' => 'static'
    ],    
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
