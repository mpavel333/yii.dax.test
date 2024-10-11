
<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

return [
    [
        'attribute' => 'order',
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
        'label' => 'Дата документов',
        'visible'=>\app\models\Flight::isVisibleAttr('date_cr'),
        'format'=> ['date', 'php:d.m.Y'],
    ], 


    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'upd',
        'visible'=>\app\models\Flight::isVisibleAttr('upd'),
    ], 

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'label' => 'Организация водителя',
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
        'label' => 'Нам платит заказчик',
    ],

    [
        'attribute' => 'payment_out',
        'label' => 'Мы платим водителю',
    ],

    [
        'attribute' => 'driver_id',
        'value' => 'driver.data',
        'label' => 'Водитель',
    ],

    // [
    //     'attribute' => 'driver_id',
    //     'value' => 'driver.driver',
    //     'label' => 'Данные водителя',
    // ],


    [
        'attribute' => 'driver_id',
        'value' => 'driver.data_avto',
        'label' => 'Номер машины',
    ],

    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'number',
    //     'visible'=>\app\models\Flight::isVisibleAttr('number'),
    // ], 




    // [
    //     'attribute' => 'pay_us',
    // ],



    [
        'attribute' => 'otherwise2',
    ],

    [
        'label' => 'Дата разгрузки',
        'attribute' => 'date_out4',
        'format' => ['date', 'php:d.m.Y'],
    ],

    [
        'attribute' => 'shipping_date',
        'label' => 'Дата разгрузки',
        'format' => ['date', 'php:d.m.Y'],
    ],

    // [
    //     'attribute' => 'payment_out',
    //     'label' => 'Мы платим водителю НДС',
    // ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'label' => 'ИНН Водителя',
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->carrier_id);

            if($carrier){
                return $carrier->inn;
            }

        },
        // ''
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
        'attribute' => 'act',
        'label' => 'Акт водителя',
    ],
    [
        'attribute' => 'act_date',
        'label' => 'Дата акта водителя',
        'format' => ['date', 'php:d.m.Y'],
    ],

        [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'organization_id',
        'label' => 'ИНН Заказчика',
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->zakazchik_id);


            if($carrier){
                return $carrier->inn;
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

