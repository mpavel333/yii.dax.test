
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
            return Url::to(["mail"."/".$action,'id'=>$key]);
    },
    'template' => '{copy} {update} {delete}',
   'buttons' => [
            'copy' => function($url, $model){
                return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите копировать эту позицию']);
            },
   ],
    'viewOptions'=>['data-pjax'=>'0','title'=>'Просмотр','data-toggle'=>'tooltip'],
    'updateOptions'=>['role'=>'modal-remote','title'=>'Изменить', 'data-toggle'=>'tooltip'],
    'deleteOptions'=>['role'=>'modal-remote','title'=>'Удалить', 
                      'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                      'data-request-method'=>'post',
                      'data-toggle'=>'tooltip',
                      'data-confirm-title'=>'Вы уверены?',
                      'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию'], 
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'user_id',
         'value'=>'user.name',
         'label' => 'ФИО',
         'visible'=>\app\models\Mail::isVisibleAttr('user_id'),
    ],
 
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'organization_name',
         'label' => 'Название организации кому отправляем',
         'visible'=>\app\models\Mail::isVisibleAttr('organization_name'),
    ],
    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //      'attribute'=>'from',
    //      'visible'=>\app\models\Mail::isVisibleAttr('from'),
    //      'filter' => '',
    // ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'information',
         'label' => "Информация письма",
         'visible'=>\app\models\Mail::isVisibleAttr('to'),
         'filter' => '',
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'track',
         'visible'=>\app\models\Mail::isVisibleAttr('track'),
         'filter' => '',
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'when_send',
        'visible'=>\app\models\Mail::isVisibleAttr('when_send'),
        'format'=> ['date', 'php:d.m.Y'],
        'filter' => '',
    ],

];   

