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
/* @var $searchModel TicketMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Сообщения технической поддержки";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['ticket-message/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['ticket-message/create'];
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


<?php \yii\widgets\Pjax::begin(['id' => 'main-page-pjax']) ?>

<?php if(isset($_GET['m']) == false || $_GET['m'] == 0): ?>
<div class="panel panel-inverse project-index">
    <div class="panel-heading">
        <!--        <div class="panel-heading-btn">-->
        <!--        </div>-->
  
        <h4 class="panel-title"><?=  Yii::t('app', "Сообщения технической поддержки")?></h4>
  
    </div>
    
    <div class="panel-body">
        <div class="ticket-message-index">
            <div id="ajaxCrudDatatable">
<?php if(Yii::$app->user->identity->can("ticket_message_index") == true):?>
                         

        <?=GridView::widget([
                    'id'=>'crud-datatable-ticket_message',                    'dataProvider' => $dataProvider,
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
                        <?php endif; ?>             </div>

        </div>
    </div>
</div>
<?php else: ?>
           
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
                <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
                                ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
            </div>
        </div>
    </div>   

<?php \yii\widgets\Pjax::begin(['id' => 'crud-datatable-ticket_message-pjax', 'enablePushState' => false]) ?>
<?= \app\widgets\BlockView::widget([
    'dataProvider' => $dataProvider,
    'columns' => require(__DIR__.'/_columns.php'),
]) ?>
<?php \yii\widgets\Pjax::end() ?>

<?php endif; ?>

<?php
$mobUrl = [Url::base()];
$mobUrl = \yii\helpers\ArrayHelper::merge($mobUrl, $_GET);
$mValue = isset($_GET['m']) ? $_GET['m'] : 0;
$mValueFld = $mValue == 1 ? 0 : 1;
if(isset($mobUrl['m'])){
    unset($mobUrl['m']);
}
?>
<?php $mobForm = \yii\widgets\ActiveForm::begin(['id' => 'mobform', 'method' => 'get', 'action' => $mobUrl, 'options' => ['data-pjax' => true]]) ?>
    <input type="hidden" name="m" value="<?= $mValueFld ?>">
<?php \yii\widgets\ActiveForm::end() ?>

<?php \yii\widgets\Pjax::end() ?>


<?php
$script = <<< JS

window.mobileMode = {$mValue};

console.log(window.mobileMode, 'window.mobileMode');

$(window).on('resize', function(){
    var height = window.innerHeight;
    var width = window.innerWidth;


    if(width <= 767){
        if(window.mobileMode == 0){
            console.log(1, 'mobile mode');
            window.mobileMode = 1;
            $('#mobform').submit();
        }
    } else {
        if(window.mobileMode == 1){
            console.log(0, 'mobile mode');
            window.mobileMode = 0;
            $('#mobform').submit();
        }
    }
});

$(window).trigger('resize');

JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?>

           
<?php
$script = <<< JS
$('#crud-datatable-ticket_message-container [data-key]').dblclick(function(e){
    if($(e.target).is('td')){
        var id = $(this).data('key');
        window.location = '/ticket-message/view?id='+id;
    }
});

$(function(){
    $(':input').click(function(){ 
        input_temp=this.value;
        $(this).select().focus();
    });
});

$(document).on('pjax:complete' , function(event) {
    $('#crud-datatable-ticket_message-container [data-key]').dblclick(function(e){
        if($(e.target).is('td')){
            var id = $(this).data('key');
            window.location = '/ticket-message/view?id='+id;
        }
    });
    $(function(){
        $(':input').click(function(){ 
            input_temp=this.value;
            $(this).select().focus();
        });
    });
});
function convert_to_float(a) {
    var floatValue = +(a);
    return floatValue;
}
function maxLengthCheck(object)
{
    if (convert_to_float(object.value) > convert_to_float(object.max)) 
      object.value = object.max
}
JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?> 
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
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
