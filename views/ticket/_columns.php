
<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],


    [
    'class' => 'kartik\grid\ActionColumn',
    'dropdown' => false,
    'vAlign'=>'middle',
    'urlCreator' => function($action, $model, $key, $index) { 
            return Url::to(["ticket"."/".$action,'id'=>$key]);
    },
   
    'viewOptions'=>['data-pjax'=>'0','title'=>'Просмотр','data-toggle'=>'tooltip', 'style' => (\Yii::$app->user->identity->can('ticket_view') == false ? "display: none;" : null)],
    'updateOptions'=>['role'=>'modal-remote','title'=>'Изменить', 'data-toggle'=>'tooltip', 'style' => (\Yii::$app->user->identity->can('ticket_update') == false ? "display: none;" : null)],
    'deleteOptions'=>['role'=>'modal-remote','title'=>'Удалить', 
                      'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                      'data-request-method'=>'post',
                      'data-toggle'=>'tooltip',
                      'data-confirm-title'=>'Вы уверены?',
                      'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию', 'style' => (\Yii::$app->user->identity->can('ticket_delete') == false ? "display: none;" : null)], 
    ],
   
   
 
        [
        'class'=>'\kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'subject',
        'visible'=>1,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'status',
        'visible' => 1,
        'format' =>['decimal', 2],
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'user_id',
        'visible'=>0,
        'value'=>'user.name',
        'filter'=> ArrayHelper::map(\app\models\User::find()->asArray()->all(), 'id', 'name'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'user_service_id',
        'visible'=>1,
        'label' => 'Пользователь',
        'value'=>'userService.login',
        'filter'=> ArrayHelper::map(\app\models\User::find()->asArray()->all(), 'id', 'login'),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
          'convertFormat'=>true,
          'pluginEvents' => [
              'cancel.daterangepicker'=>'function(ev, picker) { $($("input[name=\'TicketSearch[create_at]\']")).val(null).trigger("change"); }',
              'apply.daterangepicker' => 'function(event, picker) {                   var startDate = picker.startDate.format("YYYY-MM-DD");                  var endDate = picker.endDate.format("YYYY-MM-DD");                  if(startDate == endDate){                      var searchValue = startDate+" - "+endDate;                      $("input[name=\'TicketSearch[create_at]\']").val(searchValue).trigger("change");                  }                 }',           ],
           'pluginOptions' => [
              'opens'=>'right',
              'locale' => [
                  'cancelLabel' => 'Clear',
                  'format' => 'Y-m-d',
               ]
           ]
         ],
        'attribute'=>'create_at',
        'visible'=>0,
        'format'=> ['date', 'php:d.m.Y H:i'],
    ],

];   

