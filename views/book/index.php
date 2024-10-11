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
/* @var $searchModel BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Брэнд";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$controllerName = str_replace('_', '-', \Yii::$app->controller->tableName);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge([$controllerName.'/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = [$controllerName.'/create'];
}

?>


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
</style>

<div class="panel panel-inverse project-index">
    <div class="panel-heading">
        <!--        <div class="panel-heading-btn">-->
        <!--        </div>-->
  
        <h4 class="panel-title"><?=  Yii::t('app', \Yii::$app->controller->headerLabel)?></h4>
  
    </div>
    
    <div class="panel-body">
        <div class="brand-index">
            <div id="ajaxCrudDatatable">
                        <?=GridView::widget([
                    'id'=>'crud-datatable-brand',
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'pjax'=>true,
                    'columns' => require(__DIR__.'/_columns.php'),
                                'panelBeforeTemplate' =>    Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
                        ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']). '     '.Html::a(Yii::t('app', 'Импорт'), ['add'],
                            ['role'=>'modal-remote','title'=> 'Импорт', 'class'=>'btn btn-warning']),
                             
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
                ])?>
                        </div>

        </div>
    </div>
</div>
           
<?php
$script = <<< JS
$('[data-key]').dblclick(function(e){
    if($(e.target).is('td')){
        var id = $(this).data('key');
        window.location = '/brand/view?id='+id;
    }
});

$(function(){
    $(':input').click(function(){ 
        input_temp=this.value;
        $(this).select().focus();
    });
});

$(document).on('pjax:complete' , function(event) {
    $('[data-key]').dblclick(function(e){
        if($(e.target).is('td')){
            var id = $(this).data('key');
            window.location = '/brand/view?id='+id;
        }
    });
    $(function(){
        $(':input').click(function(){ 
            input_temp=this.value;
            $(this).select().focus();
        });
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?> 
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "options" => [ 
        "tabindex" => false, 
    ], 
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
