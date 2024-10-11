
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
            return Url::to(["login-connect"."/".$action,'id'=>$key]);
    },
   
    'viewOptions'=>['data-pjax'=>'0','title'=>'Просмотр','data-toggle'=>'tooltip', 'style' => (\Yii::$app->user->identity->can('login_connect_view') == false ? "display: none;" : null)],
    'updateOptions'=>['role'=>'modal-remote','title'=>'Изменить', 'data-toggle'=>'tooltip', 'style' => (\Yii::$app->user->identity->can('login_connect_update') == false ? "display: none;" : null)],
    'deleteOptions'=>['role'=>'modal-remote','title'=>'Удалить', 
                      'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                      'data-request-method'=>'post',
                      'data-toggle'=>'tooltip',
                      'data-confirm-title'=>'Вы уверены?',
                      'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию', 'style' => (\Yii::$app->user->identity->can('login_connect_delete') == false ? "display: none;" : null)], 
    ],
   
   
 
        [
        'class'=>'\kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'ip_address',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'status',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'login',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'password',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'code',
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
          'convertFormat'=>true,
          'pluginEvents' => [
              'cancel.daterangepicker'=>'function(ev, picker) { $($("input[name=\'LoginConnectSearch[create_at]\']")).val(null).trigger("change"); }',
              'apply.daterangepicker' => 'function(event, picker) {                   var startDate = picker.startDate.format("YYYY-MM-DD");                  var endDate = picker.endDate.format("YYYY-MM-DD");                  if(startDate == endDate){                      var searchValue = startDate+" - "+endDate;                      $("input[name=\'LoginConnectSearch[create_at]\']").val(searchValue).trigger("change");                  }                 }',           ],
           'pluginOptions' => [
              'opens'=>'right',
              'locale' => [
                  'cancelLabel' => 'Clear',
                  'format' => 'Y-m-d',
               ]
           ]
         ],
        'attribute'=>'create_at',
        'format'=> ['date', 'php:d.m.Y H:i'],
    ],

];   

