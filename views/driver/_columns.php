
<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
        'visible' => Yii::$app->user->identity->isSuperAdmin(),
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
                return Url::to(["driver"."/".$action,'id'=>$key]);
        },
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
         'attribute'=>'data',
         'visible'=>\app\models\Driver::isVisibleAttr('data'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'driver',
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'phone',
         'visible'=>\app\models\Driver::isVisibleAttr('phone'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'data_avto',
         'visible'=>\app\models\Driver::isVisibleAttr('data_avto'),
    ],

];   

