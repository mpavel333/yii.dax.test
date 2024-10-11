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

    table thead tr th, table thead tr td {
        background: #ebeb67 !important;
        border-color: #bfbf47 !important;
    }

    [data-payed='1'] td {
        background: #cee !important;
        border-color: #99dede !important;
    }

    [data-unpayed='1'] td {
        background: #ffdedd !important;
        border-color: #ffbdbc !important;
    }

</style>

<div class="panel panel-inverse project-index">
    <div class="panel-heading">
        <!--        <div class="panel-heading-btn">-->
        <!--        </div>-->
        <h4 class="panel-title"><?=  Yii::t('app', "Рейсы")?></h4>
  
    </div>
    
    <div class="panel-body">
        <div class="flight-index">
            <div id="ajaxCrudDatatable">
                        <?php
                        echo GridView::widget([
                    'id'=>'crud-datatable-flight',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pjax'=>true,
                    'columns' => require(__DIR__.'/_columns2.php'),
                                'panelBeforeTemplate' =>    Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
                        ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']).' '.
                                Html::a(Yii::t('app', 'Экспорт').' <i class="fa fa-file-excel-o"></i>', ArrayHelper::merge(['flight/export2'], Yii::$app->request->queryParams), ['class' => 'btn btn-warning', 'data-pjax' => 0, 'download' => true]),
                    'rowOptions' => function($model){
                        if($model->is_payed == true){
                            return ['data-payed' => 1];
                        } else {
                            return ['data-unpayed' => 1];
                        }
                    },
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,  
                    'responsiveWrap' => false,        
                    'panel' => [
                        'headingOptions' => ['style' => 'display: none;'],
                        'after'=>BulkButtonWidget::widget([
                                    'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; '.Yii::t('app', "Удалить все"),
                                        ["bulk-delete"] ,
                                        [
                                            "class"=>"btn btn-danger btn-xs",
                                            'role'=>'modal-remote-bulk',
                                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                            'data-request-method'=>'post',
                                            'data-confirm-title'=>'Вы уверены?',
                                            'data-confirm-message'=>Yii::t('app', "Вы действительно хотите удалить эту запись?")
                                        ]),
                                ]).                        
                                '<div class="clearfix"></div>',
 
                    ]
                ])

                        // echo DynaGrid::widget([
                        //     'columns'=>require(__DIR__.'/_columns.php'),
                        //     'storage'=>DynaGrid::TYPE_COOKIE,
                        //     'theme'=>'panel-danger',
                        //     'gridOptions'=>[
                        //         'dataProvider'=>$dataProvider,
                        //         'filterModel'=>$searchModel,
                        //         'panel'=>null,
                        //     ],
                        //     'options'=>['id'=>'dynagrid-1'] // a unique identifier is important
                        // ]);
                ?>
                        </div>

        </div>
    </div>
</div>
            
<?php
$script = <<< JS
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
JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
