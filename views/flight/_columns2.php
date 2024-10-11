
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
                return Url::to(["flight"."/".$action,'id'=>$key]);
        },
        'template' => '{copy} {print} {view} {update} {delete}',
        'buttons' => [
            'print' => function($url, $model){
                return Html::a('<span class="glyphicon glyphicon-print"></span>', ['print', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Печать']);
            },
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
        'value' => 'driver.driver',
        'label' => 'Водитель',
    ],


    [
        'attribute' => 'driver_id',
        'value' => 'driver.data_avto',
        'label' => 'Номер машины',
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'number',
        'visible'=>\app\models\Flight::isVisibleAttr('number'),
    ], 




    [
        'attribute' => 'pay_us',
    ],



    [
        'attribute' => 'otherwise2',
    ],

    [
        'label' => 'Дата разгрузки',
    ],

    [
        'attribute' => 'shipping_date',
        'label' => 'Дата разгрузки',
        'format' => ['date', 'php:d.m.Y'],
    ],

    [
        'label' => 'Мы платим водителю НДС',
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'label' => 'ИНН Водителя',
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->carrier_id);

            return $carrier->inn;

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
        'attribute'=>'organization_id',
        'label' => 'ИНН Заказчика',
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->organization_id);



            return $carrier->inn;

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

    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'order',
         'visible'=>0,
    ],
    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'date',
    //     'visible'=>\app\models\Flight::isVisibleAttr('date'),
    //     'format'=> ['date', 'php:d.m.Y'],
    // ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'view_auto',
        'visible'=>0,
        'filter'=> \app\models\Flight::view_autoLabels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //      'attribute'=>'address1',
    //      'visible'=>\app\models\Flight::isVisibleAttr('address1'),
    // ],
    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'shipping_date',
    //     'visible'=>\app\models\Flight::isVisibleAttr('shipping_date'),
    //     'format'=> ['date', 'php:d.m.Y'],
    // ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'telephone1',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'type',
        'visible'=>0,
        'filter'=> \app\models\Flight::typeLabels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out2',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address_out2',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact_out2',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name2',
        'visible'=>0,
        'filter'=> \app\models\Flight::name2Labels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address_out3',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out3',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'name3',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address_out4',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out4',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'telephone',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'cargo_weight',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'name',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address_out5',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact_out',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out5',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'volume',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'address',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'date_out6',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact_out3',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'dop_informaciya_o_gruze',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'we',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'pay_us',
        'visible'=>0,
        'filter'=> \app\models\Flight::pay_usLabels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'payment1',
        'visible'=>0,
        'filter'=> \app\models\Flight::payment1Labels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'col2',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'payment_out',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'otherwise2',
        'visible'=>0,
        'filter'=> \app\models\Flight::otherwise2Labels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'otherwise3',
        'visible'=>0,
        'filter'=> \app\models\Flight::otherwise3Labels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => ''],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ]
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'col1',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'fio',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'number',
        'visible' => 0,
        'format' =>['decimal', 2],
        'pageSummary' => true, 
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'upd',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date2',
        'visible'=>0,
        'format'=> ['date', 'php:d.m.Y'],
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date3',
        'visible'=>0,
        'format'=> ['date', 'php:d.m.Y'],
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'recoil',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'your_text',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'otherwise4',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'otherwise',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'file',
         'visible'=>0,
    ],

];   

