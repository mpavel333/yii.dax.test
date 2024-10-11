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
/* @var $searchModel MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Сообщения";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['message/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['message/create'];
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

<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-message',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'id',
    'titleAttribute' => 'subject',
    'listOptions' => [
        'style' => 'height: 140px; overflow-y: scroll;',
    ],
    'list' => [
        [
            'attribute' => 'user_name',
            'label' => 'Пользователь',
        ],
        [
            'attribute' => 'upd',
            'label' => 'УПД',
        ],
    ],
    'buttonsTemplate' => '{messages} {delete} {view}',
    'buttons' => [
        
        'delete' => function($model){
            
            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['flight/chat-delete', 'id' => $model['id']], [
                            'class' => 'btn btn-sm btn-white',
                    'role'=>'modal-remote','title'=>'Удалить', 
                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    'data-request-method'=>'post',
                    'data-toggle'=>'tooltip',
                    'data-confirm-title'=>'Вы уверены?',
                    'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию?'
            ]);    
    
        },
        
        'view' => function($model){
            return Html::a('<i class="fa fa-weixin"></i>', ['flight/chat', 'id' => $model['flight_id'], 'user' => $model['user_id']], [
                'class' => 'btn btn-sm btn-white',
                'role'=>'modal-remote','title'=>'Чат',
            ]);
        },
        'messages' => function($model){
            if($model['unread_messages'] > 0){
                return "<span class='label label-danger' style='font-size: 10px;' title='Непрочитанные сообщения'><i class='fa fa-envelope'></i> ".$model['unread_messages']."</span>";
            }
        },
    ],
]) ?>

<?php \yii\widgets\Pjax::end() ?>




           
<?php
$script = <<< JS
$('#crud-datatable-message-container [data-key]').dblclick(function(e){
    if($(e.target).is('td')){
        var id = $(this).data('key');
        window.location = '/message/view?id='+id;
    }
});

$(function(){
    $(':input').click(function(){ 
        input_temp=this.value;
        $(this).select().focus();
    });
});

$(document).on('pjax:complete' , function(event) {
    $('#crud-datatable-message-container [data-key]').dblclick(function(e){
        if($(e.target).is('td')){
            var id = $(this).data('key');
            window.location = '/message/view?id='+id;
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
