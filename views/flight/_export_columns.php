<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

return [   
    [
        'attribute' => 'id',
        'label' => 'Заявка',
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date',
        'visible'=>\app\models\Flight::isVisibleAttr('date'),
        'label' => 'Дата',
        'format'=> ['date', 'php:d.m.Y'],
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'rout',
         'visible'=>\app\models\Flight::isVisibleAttr('rout'),
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'zakazchik_id',
        'visible'=>\app\models\Flight::isVisibleAttr('zakazchik_id'),
        'value'=>'zakazchik.name',
        'filter'=> ArrayHelper::map(\app\models\Client::find()->asArray()->all(), 'id', 'name'),
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
        'attribute'=>'date_cr',
        'visible'=>\app\models\Flight::isVisibleAttr('date_cr'),
        'format'=> ['date', 'php:d.m.Y'],
    ], 

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'number',
        'visible'=>\app\models\Flight::isVisibleAttr('number'),
    ], 

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'upd',
        'visible'=>\app\models\Flight::isVisibleAttr('upd'),
    ], 

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'visible'=>\app\models\Flight::isVisibleAttr('carrier_id'),
        'value'=>'carrier.name',
        'filter'=> ArrayHelper::map(\app\models\Client::find()->asArray()->all(), 'id', 'name'),
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
        'attribute' => 'we',
    ],

    [
        'attribute' => 'pay_us',
    ],

    [
        'attribute' => 'payment_out',
    ],

    [
        'attribute' => 'otherwise2',
    ],




    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'label' => 'АТИ Водителя',
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->carrier_id);

            if($carrier){
              return $carrier->code;
            }
        },
        // 'visible'=>\app\models\Flight::isVisibleAttr('driver_id'),
        // 'value'=>'driver.data',
        // 'filter'=> ArrayHelper::map(\app\models\Driver::find()->asArray()->all(), 'id', 'data'),
        // 'filterType'=> GridView::FILTER_SELECT2,
        // 'filterWidgetOptions'=> [
        //        'options' => ['prompt' => '', 'multiple' => true],
        //        'pluginOptions' => [
        //               'allowClear' => true,
        //               'tags' => false,
        //               'tokenSeparators' => [','],
        //        ]
        // ]
    ],


        [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'organization_id',
        'label' => 'АТИ Заказчика',
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->organization_id);

            if($carrier){
              return $carrier->code;
            }
        },
        // 'visible'=>\app\models\Flight::isVisibleAttr('organization_id'),
        // 'value'=>'organization.name',
        // 'filter'=> ArrayHelper::map(\app\models\Requisite::find()->asArray()->all(), 'id', 'name'),
        // 'filterType'=> GridView::FILTER_SELECT2,
        // 'filterWidgetOptions'=> [
        //        'options' => ['prompt' => '', 'multiple' => true],
        //        'pluginOptions' => [
        //               'allowClear' => true,
        //               'tags' => false,
        //               'tokenSeparators' => [','],
        //        ]
        // ]
    ],
];   

