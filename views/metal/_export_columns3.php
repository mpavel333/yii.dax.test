
<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

return [
    [
        'attribute' => 'id',
        'label' => 'Менеджер',
        'content' => function($model){
            $user = \app\models\User::findOne($model->created_by);

            if($user){
                return $user->role;
            }
        }
    ],

    [
        'attribute' => 'order',
        'label' => 'Заявка №',
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date',
        'visible'=>\app\models\Flight::isVisibleAttr('date'),
        'label' => 'Дата Заявки',
        'format'=> ['date', 'php:d.m.Y'],
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'rout',
         'label' => 'Рейс',
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
        'attribute'=> 'number',
    ], 


    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'distance',
        'visible'=>\app\models\Flight::isVisibleAttr('distance'),
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
        // 'value' => 'driver.driver',
        'content' => function($model){

            $driver = \app\models\Driver::findOne($model->driver_id);

            // return ArrayHelper::getValue($model, 'driver.data').' '.ArrayHelper::getValue($model, 'driver.data_avto').' '.ArrayHelper::getValue($model, 'driver.car_number').' '.ArrayHelper::getValue($model, 'driver.car_truck_number');
            if($driver){
                return "{$driver->data}";
            }
        },
        'label' => 'Водитель',
    ],


    [
        'attribute' => 'auto',
        // 'value' => 'driver.data_avto',
        'content' => function($model){
            $driver = \app\models\Driver::findOne($model->auto);


            // return ArrayHelper::getValue($model, 'driver.data_avto').' '.ArrayHelper::getValue($model, 'driver.car_number').' '.ArrayHelper::getValue($model, 'driver.car_truck_number');

            if($driver){
                return "{$driver->data_avto} {$driver->car_number} {$driver->car_truck_number}";
            }
        },
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
        'attribute' => 'shipping_date',
        'format' => ['date', 'php:d.m.Y'],
    ],
    [
        'attribute' => 'date_out4',
        'format' => ['date', 'php:d.m.Y'],
    ],

    [
        'attribute' => 'col1',
    ],
    [
        'attribute' => 'col2',
    ],

    // [
    //     'attribute' => 'we',
    // ],


    // [
    //     'attribute' => 'date_out4',
    //     'format' => ['date', 'php:d.m.Y'],
    // ],

    // [
    //     'label' => 'Мы платим водителю НДС',
    // ],


    [
        'attribute' => 'otherwise2',
    ],
    [
        'attribute' => 'pay_us',
    ],

    [
        'attribute' => 'salary',
        'format' => ['integer'],
    ],

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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'act',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'act_date',
        'format' => ['date', 'php:d.m.Y'],
    ],

    [
        'attribute' => 'organization_id',
        "label" => 'Организация',
        'value' => 'organization.name',
    ],

    [
        'attribute' => 'recoil',
        'label' => 'Откат',
        'content' => function($model){
            if (!$model->recoil) {
                return 0;
            }
            if ($model->recoil > 0) {
                return $model->recoil;
            } else {
                return 0;
            }
        },
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'zakazchik_id',
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

