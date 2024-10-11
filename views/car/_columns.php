<?php
use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'number',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'status',
        'value' => function($model){
            return \yii\helpers\ArrayHelper::getValue(\app\models\Car::statusLabels(), $model->status);
        },
        'filter' => \app\models\Car::statusLabels(),
        'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['prompt' => 'Выберите'],
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => false,
                'tokenSeparators' => [','],
            ],
        ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'driver_id',
        'value' => 'driver.data',
        'filter' => \yii\helpers\ArrayHelper::map(\app\models\Driver::find()->all(), 'id', 'data'),
        'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['prompt' => 'Выберите'],
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => false,
                'tokenSeparators' => [','],
            ],
        ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'mileage',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{print} {view}{update}{delete}',
        'buttons' => [
            'print' => function($url, $model){
                return Html::a('<span class="glyphicon glyphicon-print"></span>', ['car/print', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Печать']);
            },
        ], 
        'viewOptions'=>['role'=>'modal-remote','title'=>'Просмотр','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Изменить', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Удалить', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены, что хотите удалить авто?'], 
    ],

];   