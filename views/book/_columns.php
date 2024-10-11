
<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

$controllerName = str_replace('_', '-', \Yii::$app->controller->tableName);

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
    'template' => '{update} {delete}',
    'urlCreator' => function($action, $model, $key, $index) use($controllerName) { 
            return Url::to([$controllerName."/".$action,'id'=>$key]);
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
        'format'=>'raw',
        'attribute'=>'name',
        'visible'=>1,
    ],

];   

