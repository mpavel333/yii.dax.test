<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\helpers\ArrayHelper;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Рейсы";
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
        Html::a('Оплата от заказчика', $clientPaymentUrl, ['class' => 'btn btn-'.($clientPayment ? 'danger' : 'white'), 'style' => 'margin-left: 25px;']).' '.Html::a('Оплата водителя', $driverPaymentUrl, ['class' => 'btn btn-'.($driverPayment ? 'danger' : 'white')]);
} else {
    $exportButtons = '';
}

?>
<style>
    .modal-dialog {
        width: 80% !important;
    }

/*    .sidebar {
        width: 181px !important;
    }
*/
/*    .content {
        margin-left: 174px !important;
    }*/
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

    #crud-datatable-flight #crud-datatable-flight-container {
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

<div class="panel panel-inverse project-index">
    <div class="panel-heading">
        <!--        <div class="panel-heading-btn">-->
        <!--        </div>-->
        <h4 class="panel-title"><?=  Yii::t('app', "Рейсы")?></h4>
  
    </div>
    <?php if(\Yii::$app->user->identity->can('flight_statistic')): ?>
    <div class="panel-before" style="background: #f5f5f5; padding: 7px 14px; border-bottom: 1px solid #e1e1e1;">
    <?php

        $flightRegister = \app\models\Flight::find()->where(['>=', 'created_at', date('Y-m-d').' 00:00:00'])->andWhere(['is_register' => true])->count();
        $flightToday = \app\models\Flight::find()->where(['>=', 'created_at', date('Y-m-d').' 00:00:00'])->all();
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
                // $delta = $delta + $flight->calculateDelta($user);
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
                // $salary = $salary + (doubleval($flight->payment_out) - doubleval($flight->we) - doubleval($flight->recoil));
                // $salary = $salary + $flight->calculateSalary($user);
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
                <p style="margin-bottom: 0;">Общая сумма: <?= number_format(($delta - $salary), 0, '.', ' ') ?></p>
            </div>
            <div class="col-md-3">
                <p style="margin-bottom: 0;">Зарлата за день: <?= number_format($salary, 0, '.', ' ') ?> руб.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="panel-body">
        <div class="flight-index">
            <div id="ajaxCrudDatatable">
                        <?php
                //         echo GridView::widget([
                //     'id'=>'crud-datatable-flight',
                //     'dataProvider' => $dataProvider,
                //     'filterModel' => $searchModel,
                //     'pjax'=>true,
                //     'columns' => require(__DIR__.'/_columns.php'),
                //                 'panelBeforeTemplate' =>    Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
                //         ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']).' '.$exportButtons,
                //     'rowOptions' => function($model){
                //         if($model->is_payed == true){
                //             // return ['data-payed' => 1];
                //         } else {
                //             // return ['data-unpayed' => 1];
                //         }
                //     },
                //     'floatHeader' => true,
                //     'striped' => true,
                //     'condensed' => true,
                //     'responsive' => true,  
                //     'responsiveWrap' => false,  
                //     'showPageSummary' => true,      
                //     'perfectScrollbar' => true,
                //     'panel' => [
                //         'headingOptions' => ['style' => 'display: none;'],
                //         'after'=>BulkButtonWidget::widget([
                //                     'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; '.Yii::t('app', "Удалить все"),
                //                         ["bulk-delete"] ,
                //                         [
                //                             "class"=>"btn btn-danger btn-xs",
                //                             'role'=>'modal-remote-bulk',
                //                             'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                //                             'data-request-method'=>'post',
                //                             'data-confirm-title'=>'Вы уверены?',
                //                             'data-confirm-message'=>Yii::t('app', "Вы действительно хотите удалить эту запись?")
                //                         ]),
                //                 ]).                        
                //                 '<div class="clearfix"></div>',
 
                //     ]
                // ]);

                        $wrenchBtn = '';

                        if(\Yii::$app->user->identity->can('flight_table'))
                        {
                            $wrenchBtn = Html::a('<i class="fa fa-wrench"></i>', '#', ['class' => 'btn btn-danger', 'onclick' => '$(\'#dynagrid-two-1-grid-modal\').modal(\'show\');']);
                        }

						echo Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
                        ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']).' '.$wrenchBtn.' '.$exportButtons;?>



<?php \yii\widgets\Pjax::begin(['id' => 'crud-datatable-flight-pjax']) ?>

<?php                        echo \kartik\dynagrid\DynaGrid::widget([
    'columns'=>require(__DIR__.'/_columns.php'),
    'storage'=>\kartik\dynagrid\DynaGrid::TYPE_COOKIE,
    'showPersonalize'=>true,
    'theme'=>'panel-danger',
	'allowSortSetting' => false,
    'allowPageSetting' => true,
    'allowFilterSetting' => false,
    'allowThemeSetting' => false,
    'gridOptions'=>[
        'floatHeader' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,  
        'responsiveWrap' => false,  
        'showPageSummary' => true,      
        'perfectScrollbar' => true,
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'pjax' => true,
		'panel'=>[
            'heading'=>'',
            'before' =>  '<div style="padding-top: 7px;"><em></em></div>',
            'after'=>BulkButtonWidget::widget([
                                    'buttons'=>Html::a('<i class="fa fa-archive"></i>&nbsp; '.Yii::t('app', "Архив"),
                                        ["bulk-archive"],
                                        [
                                            "class"=>"btn btn-warning btn-xs",
                                            'role'=>'modal-remote-bulk',
                                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                            'data-request-method'=>'post',
                                            'data-confirm-title'=>'Вы уверены?',
                                            'data-confirm-message'=>Yii::t('app', "Вы действительно хотите удалить эту запись?")
                                        ]).' '.(\Yii::$app->user->identity->isSuperAdmin() ? Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; '.Yii::t('app', "Удалить все"),
                                        ["bulk-delete"] ,
                                        [
                                            "class"=>"btn btn-danger btn-xs",
                                            'role'=>'modal-remote-bulk',
                                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                            'data-request-method'=>'post',
                                            'data-confirm-title'=>'Вы уверены?',
                                            'data-confirm-message'=>Yii::t('app', "Вы действительно хотите удалить эту запись?")
                                        ]) : ''),
                                ]).                        
                                '<div class="clearfix"></div>',
        ],
        'toolbar' =>  [
            ['content'=> '',
                '<button type="button" class="btn btn-danger" title="Персонализировать настройки таблицы" onclick="$(\'#dynagrid-1-grid-modal\').modal(\'show\');" ><i class="glyphicon glyphicon-wrench"></i></button>',
            ],
            ['content'=>'<div style="display: none;">{dynagridFilter}{dynagridSort}{dynagrid}</div>'],
            '{export}',
        ]
    ],
    'options'=>['id'=>'dynagrid-two-1'] // a unique identifier is important
]);


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

$.get('/flight/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
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

    $.get('/flight/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
        console.log(response, 'response');
    });

});

$('[data-multiple-e]').change(function(){

    var key = $(this).data('multiple-e');
    var attr = $(this).data('attr');
    var id = $(this).data('id');
    var value = $(this).is(':checked');

    $.get('/flight/update-checks?id='+id+'&attr='+attr+'&key='+key+'&value='+value, function(response){
        // console.log(response, 'response');
    });

});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

                ?>



<?php \yii\widgets\Pjax::end() ?>


                        </div>

        </div>
    </div>
</div>
            
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
