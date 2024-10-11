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
/* @var $searchModel TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Техническая поддержка";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['ticket/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['ticket/create'];
}


$role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);

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

<?php if($role->ticket_manager == false): ?>
<div class="row">
  <div class="col-md-12" style="margin-bottom: 10px;">
          <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['ticket/create'],
                          ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
  </div>
</div>
<?php endif; ?>

<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable',
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
            'attribute' => 'status',
            'label' => 'Статус',
            'content' => function($model){
                if($model->status == \app\models\Ticket::STATUS_AWAIT){
                    return "<span class='label label-warning'>".\yii\helpers\ArrayHelper::getValue(\app\models\Ticket::statusLabels(), $model->status)."</span>";
                } elseif($model->status == \app\models\Ticket::STATUS_AT_WORK){
                    return "<span class='label label-info'>".\yii\helpers\ArrayHelper::getValue(\app\models\Ticket::statusLabels(), $model->status)."</span>";
                } elseif($model->status == \app\models\Ticket::STATUS_DONE){
                    return "<span class='label label-default'>".\yii\helpers\ArrayHelper::getValue(\app\models\Ticket::statusLabels(), $model->status)."</span>";
                }
            },
        ],
        [
            'attribute' => 'user_id',
            'label' => 'Пользователь',
            'visible' => $role->ticket_manager,
            'content' => function($model){
                return \yii\helpers\ArrayHelper::getValue($model, 'user.name');
            },
        ],
        [
            'attribute' => 'create_at',
            'label' => 'Дата и время',
            'content' => function($model){
                return \Yii::$app->formatter->asDate($model->create_at, 'php:d.m.Y H:i');
            },
        ],
    ],
    'buttonsTemplate' => '{messages} {view}',
    'buttons' => [
        'view' => function($model){
            return Html::a('<i class="fa fa-eye"></i>', ['ticket/view', 'id' => $model->id], ['class' => 'btn btn-sm btn-white', 'data-pjax' => 0]);
        },
        'messages' => function($model){
            $messagesCount = \app\models\TicketMessage::find()->where(['ticket_id' => $model->id])->andWhere(['!=', 'user_id', \Yii::$app->user->getId()])->andWhere(['is', 'is_read', null])->count();
            if($messagesCount > 0){
                return "<span class='label label-danger' style='font-size: 10px;' title='Непрочитанные сообщения'><i class='fa fa-envelope'></i> {$messagesCount}</span>";
            }
        },
    ],
]) ?>

<?php \yii\widgets\Pjax::end() ?>



           
<?php
$script = <<< JS
$('#crud-datatable-ticket-container [data-key]').dblclick(function(e){
    if($(e.target).is('td')){
        var id = $(this).data('key');
        window.location = '/ticket/view?id='+id;
    }
});

$(function(){
    $(':input').click(function(){ 
        input_temp=this.value;
        $(this).select().focus();
    });
});

$(document).on('pjax:complete' , function(event) {
    $('#crud-datatable-ticket-container [data-key]').dblclick(function(e){
        if($(e.target).is('td')){
            var id = $(this).data('key');
            window.location = '/ticket/view?id='+id;
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
