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
/* @var $searchModel SecuritySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Безопасность";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['security/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['security/create'];
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
  
        <h4 class="panel-title"><?=  Yii::t('app', "Безопасность")?></h4>
  
    </div>
    
    <div class="panel-body">
        <div class="security-index">
            <div id="ajaxCrudDatatable">
<?php if(Yii::$app->user->identity->can("security_index") == true):?>
                         

        <?=GridView::widget([
                    'id'=>'crud-datatable-security',                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'pjax'=>true,
                    'columns' => require(__DIR__.'/_columns.php'),
                                'panelBeforeTemplate' =>    Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
                        ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']),
                   
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
                        <?php endif; ?>             </div>

        </div>
    </div>
</div>
 
<script>
    function convert_to_float(a) {
        var floatValue = +(a);
        return floatValue;
    }
    function maxLengthCheck(object)
    {
        if (convert_to_float(object.value) > convert_to_float(object.max)){
          object.value = object.max
        }
    }
</script>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "options" => [ 
        "tabindex" => false, 
    ], 
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
