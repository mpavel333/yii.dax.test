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
        'class' => 'app\widgets\SerialColumnReverse',
        'contentOptions' => function($model){

            if($model->is_register){
                return ['class' => 'success'];
            }
        },
        'width' => '30px',
    ],


    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to(["flight"."/".$action,'id'=>$key]);
        },
        // 'template' => '{copy} {export3} {print} {view} {update} {delete}',
        'template' => '{print-pdf} {copy} {export3} {print} {update} {delete} {archive}',
        'buttons' => [
            'print' => function($url, $model){
                if(($model->status == 3) || (\Yii::$app->user->identity->isClient() == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin()){
                    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['flight/print', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Печать']);
                }
            },
            'update' => function($url, $model){
                if(\Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isSignaturer()){
                    if(($model->created_by == \Yii::$app->user->getId() && $model->status == 0 && $model->is_signature == false || $model->user_id == Yii::$app->user->getId() || \Yii::$app->user->identity->isSuperAdmin() || \Yii::$app->user->identity->isManager() == false) && (($model->status == 1 && \Yii::$app->user->identity->isClient()) == false && ($model->status == 1 && \Yii::$app->user->identity->isClient() || \Yii::$app->user->identity->isManager()) == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSignaturer()){
                        return Html::a('<i class="fa fa-pencil"></i>', ['flight/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать']);
                    }
                }

                if(\Yii::$app->user->identity->isClient()){
                    if($model->status != 1){
                        return Html::a('<i class="fa fa-pencil"></i>', ['flight/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать']);
                    }
                }
            },
            'export3' => function($url, $model){
                if(\Yii::$app->user->identity->isClient() == false){
                    return Html::a('<i class="fa fa-arrow-right"></i>', ['flight/export3', 'id' => $model->id], ['data-pjax' => '0', 'title' => 'Экспорт']);
                }
            },
            'print-pdf' => function($url, $model){
                if(\Yii::$app->user->identity->isClient() == false){
                    return Html::a('<i class="fa fa-file-pdf-o"></i>', ['flight/print-pdf', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Экспорт']);
                }
            },
            'copy' => function($url, $model){
                return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['flight/copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите копировать эту позицию']);
            },
            'delete' => function($url, $model){
                if(\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager() && $model->is_signature == false && $model->is_driver_signature == false)){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['flight/delete', 'id' => $model->id], [
                        'role'=>'modal-remote','title'=>'Удалить', 
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Вы уверены?',
                        'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию'
                    ]);
                }
            },
            'archive' => function($url, $model){
                    return Html::a('<i class="fa fa-inbox"></i>', ['flight/archive-back', 'id' => $model->id], [
                        'role'=>'modal-remote','title'=>'Деархивировать', 
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Вы уверены?',
                        'data-confirm-message'=>'Вы уверены что хотите деархивировать данный рейс?'
                    ]);
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
        'attribute' => 'status',
        'visible'=>\app\models\Flight::isVisibleAttr('status'),
        'filter'=> \app\models\Flight::statusLabels(),
        'filterType'=> GridView::FILTER_SELECT2,
        'filterWidgetOptions'=> [
               'options' => ['prompt' => '', 'multiple' => true],
               'pluginOptions' => [
                      'allowClear' => true,
                      'tags' => false,
                      'tokenSeparators' => [','],
               ]
        ],
        'content' => function($model){
            return Html::dropDownList('status'.$model->id, $model->status, \app\models\Flight::statusLabels(), [
                    'class' => 'form-control',
                    'prompt' => 'Выберите',
                    'onchange' => '$.get("/flight/update-attr?id='.$model->id.'&attr=status&value="+$(this).val());',
                ]);
        },

    ],

    [
        'attribute' => 'user_id',
        'visible'=>\app\models\Flight::isVisibleAttr('user_id'),
        'label' => 'Менеджер',
        'content' => function($model){
            $userName = null;
            $user = \app\models\User::findOne($model->user_id);

            if($user){
                $userName = $user->name;
            }

            if(\Yii::$app->user->identity->can('flight_manager_change')){
                return Html::dropDownList('status'.$model->id, $model->user_id, \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'), [
                        'class' => 'form-control',
                        'prompt' => 'Выберите',
                        'onchange' => '$.get("/flight/update-attr?id='.$model->id.'&attr=user_id&value="+$(this).val());',
                    ]);
            } else {
                return $userName;
            }

        },
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
        'attribute' => 'organization_id',
        'visible'=>\app\models\Flight::isVisibleAttr('organization_id'),
        'label' => 'Наша фирма',
        'value' => 'organization.name',
        'filter'=> ArrayHelper::map(\app\models\Requisite::find()->asArray()->all(), 'id', 'name'),
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
        'attribute' => 'order',
        'visible'=>\app\models\Flight::isVisibleAttr('order'),
        'label' => 'Заявка',
        'contentOptions' => function($model){

            if($model->is_signature && $model->is_driver_signature){
                return ['class' => 'success'];
            }

            if($model->is_register){
                return ['class' => 'danger'];
            }
        },
        'width' => '10px',
        'content' => function($model){
            $output = $model->order.'<br>';

            // if(Yii::$app->user->identity->can('flight_payment_check')){
            // }

            return $output;
        },
    ],
    [
        'attribute' => 'driver_order',
        'visible'=>\app\models\Flight::isVisibleAttr('driver_order'),
        'label' => 'Заявка перевозчика',
        'width' => '10px',
        'content' => function($model){
            $output = $model->driver_order.'<br>';

            // if(Yii::$app->user->identity->can('flight_payment_check')){
            // }

            return $output;
        },
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date',
        'visible'=>\app\models\Flight::isVisibleAttr('date'),
        'filter' => '<input class="form-control" name="FlightSearch[date]" type="date" value="'.\yii\helpers\ArrayHelper::getValue($_GET, 'FlightSearch.date').'">',
        'label' => 'Дата',
        'contentOptions' => function($model){

            if($model->order_check){
                return ['class' => 'success'];
            }
        },
        'format'=> ['date', 'php:d.m.Y'],
        'content' => function($model){
            $output = \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y')."<br>";

            $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="order_check" '.($model->order_check ? 'checked="true"' : '').'">';

            return $output;
        }
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'rout',
         'visible'=>\app\models\Flight::isVisibleAttr('rout'),
         'content' => function($model){
            $output = $model->rout;

            if($model->distance){
                $output .= ' '.Html::a("{$model->distance} Км.", ['flight/distance', 'id' => $model->id], ['class' => 'label label-success', 'data-pjax' => 0, 'target' => '_blank']);
            }

            return $output;
         },
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'zakazchik_id',
        'visible'=>\app\models\Flight::isVisibleAttr('zakazchik_id'),
        'contentOptions' => function($model){
            if($model->is_signature){
                return ['class' => 'success'];
            }
        },
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
        'filter' => '<input class="form-control" name="FlightSearch[date_cr]" type="date" value="'.\yii\helpers\ArrayHelper::getValue($_GET, 'FlightSearch.date_cr').'">',
        'contentOptions' => function($model){

            if($model->date_cr_check){
                return ['class' => 'success'];
            }
        },
        'content' => function($model){
            $output = \Yii::$app->formatter->asDate($model->date_cr, 'php:d.m.Y').'<br>';

            // if(Yii::$app->user->identity->can('flight_payment_check')){
                $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="date_cr_check" '.($model->date_cr_check ? 'checked="true"' : '').'">';
            // }

            return $output;
        },
    ], 

    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'number',
    //     'visible'=>\app\models\Flight::isVisibleAttr('number'),
    //     'contentOptions' => function($model){

    //         if($model->number_check){
    //             return ['class' => 'success'];
    //         }
    //     },
    //     'content' => function($model){
    //         $output = $model->number.'<br>';

    //         // if(Yii::$app->user->identity->can('flight_payment_check')){
    //             $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="number_check" '.($model->number_check ? 'checked="true"' : '').'">';
    //         // }

    //         return $output;
    //     },
    // ], 

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'upd',
        'visible'=>\app\models\Flight::isVisibleAttr('upd'),
        'contentOptions' => function($model){
        	if($model->date2){
        		return ['class' => 'success'];
        	}
        },
    ], 

    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'visible'=>\app\models\Flight::isVisibleAttr('carrier_id'),
        'contentOptions' => function($model){
            if($model->is_driver_signature){
                return ['class' => 'success'];
            }
        },
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
         'class'=>'\kartik\grid\DataColumn',
        'label' => 'Перевозчик тел',
        'attribute' => 'carrierTel',
        'visible' => \Yii::$app->user->identity->getRoleName() != 'Заказчик',
        'value'=>'carrier.tel',
        'filter'=> ArrayHelper::map(\app\models\Client::find()->asArray()->all(), 'id', 'tel'),
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
        'attribute'=>'act',
        'contentOptions' => function($model){
            if($model->act){
                return ['class' => 'success'];
            }
        },
        'content' => function($model){
            return \app\widgets\AjaxInput::widget([
                'model' => $model,
                'attr' => 'act',
            ]);
        },
        'visible'=>\app\models\Flight::isVisibleAttr('act'),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'act_date',
        'filter' => '<input class="form-control" name="FlightSearch[act_date]" type="date" value="'.\yii\helpers\ArrayHelper::getValue($_GET, 'FlightSearch.act_date').'">',
        'format' => ['date', 'php:d.m.Y'],
        'contentOptions' => function($model){
            if($model->act_date){
                return ['class' => 'success'];
            }
        },
        'content' => function($model){
            return \app\widgets\AjaxInput::widget([
                'model' => $model,
                'format' => ['date', 'php:d.m.Y'],
                'type' => 'date',
                'attr' => 'act_date',
            ]);
        },
        'visible'=>\app\models\Flight::isVisibleAttr('act_date'),
    ],

    [
         'class'=>'\kartik\grid\DataColumn',
         'label' => 'ФИО Водителя',
        'attribute'=>'driver_id',
        'visible'=>\app\models\Flight::isVisibleAttr('driver_id'),
        'value'=>'driver.data',
        'filter'=> ArrayHelper::map(\app\models\Driver::find()->asArray()->all(), 'id', 'data'),
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
         'label' => 'Телефон Водителя',
        'attribute'=>'driver_id',
        // 'visible'=>\app\models\Flight::isVisibleAttr('driver_id'),
        'value'=>'driver.phone',
        'filter'=> ArrayHelper::map(\app\models\Driver::find()->asArray()->all(), 'id', 'phone'),
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
        'label' => 'Данные авто',
        'attribute'=>'auto',
        // 'value'=>'auto',
        'content' => function($model){
            $driver = \app\models\Driver::find()->where(['id' => $model->auto])->one();

            if($driver){
                return "{$driver->data_avto} {$driver->car_number} {$driver->car_truck_number}";
            }         
        },
        'filter'=> ArrayHelper::map(\app\models\Driver::find()->all(), 'data_avto', 'data_avto'),
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


    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'carrier_id',
    //     'visible'=>\app\models\Flight::isVisibleAttr('carrier_id'),
    //     'value'=>'carrier.name',
    // ],

    [
        'attribute' => 'we',
        'visible'=>\app\models\Flight::isVisibleAttr('we'),
        'pageSummary' => true,
        'contentOptions' => function($model){

            if($model->is_payed){
                return ['class' => 'success'];
            } elseif($model->date2 && $model->col2) {
                $date2 = new \DateTime($model->date2);

                $date2->modify("+{$model->col2} days");

                $w = $date2->format('w');

                if($w == 0){
                    $date2->modify("+2 days");
                }

                if($w == 6){
                    $date2->modify("+1 days");
                }

                $date2 = $date2->format('Y-m-d');

                \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');

                if($date2 < date('Y-m-d')){
                    return ['class' => 'danger'];
                }
            }
        },
        'content' => function($model){
            $output = number_format(doubleval($model->we), 0, '.', ' ').' руб.'.'<br>';

            if(Yii::$app->user->identity->can('flight_payment_check')){
                $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_payed" '.($model->is_payed ? 'checked="true"' : '').'">';
            }

            return $output;
        },
    ],

    // [
    //     'header' => '<i class="fa fa-rub"></i>',
    //     'content' => function($model){
    //         return '<input type="checkbox" data-id="'.$model->id.'" data-edit="is_payed" '.($model->is_payed ? 'checked="true"' : '').'">';
    //     },
    // ],


    [
        'attribute' => 'pay_us',
        'visible'=>\app\models\Flight::isVisibleAttr('pay_us'),
    ],




    [
        'attribute' => 'payment_out',
        'visible'=>\app\models\Flight::isVisibleAttr('payment_out'),
        'pageSummary' => true,
        'contentOptions' => function($model){
            if($model->is_driver_payed){
                return ['class' => 'success'];
            }
            // elseif($model->date3 && $model->col1) {
            //     if(strpos($model->col1, '—') === null){
            //         $date3 = new \DateTime($model->date3);

            //         $date3->modify("+{$model->col1} days");
                
            //         if($w == 0){
            //             $date3->modify("+2 days");
            //         }

            //         if($w == 6){
            //             $date3->modify("+1 days");
            //         }

            //         $date3 = $date3->format('Y-m-d');

            //         \Yii::warning("{$date3} > ".date('Y-m-d'), 'danger');

            //         if($date3 < date('Y-m-d')){
            //             return ['class' => 'danger'];
            //         }
            //     }
            // }
             elseif($model->date3 && $model->col1) {
                $date2 = new \DateTime($model->date3);

                $date2->modify("+{$model->col1} days");

                $w = $date2->format('w');

                if($w == 0){
                    $date2->modify("+2 days");
                }

                if($w == 6){
                    $date2->modify("+1 days");
                }

                $date2 = $date2->format('Y-m-d');

                \Yii::warning("{$date2} > ".date('Y-m-d'), 'danger');

                if($date2 < date('Y-m-d')){
                    return ['class' => 'danger'];
                }
            }
        },
        'content' => function($model){
            $output = number_format(doubleval($model->payment_out), 0, '.', ' ').' руб.'.'<br>';

            if(Yii::$app->user->identity->can('flight_payment_check')){
                $output .= '<input type="checkbox" data-id="'.$model->id.'" data-e="is_driver_payed" '.($model->is_driver_payed ? 'checked="true"' : '').'">';
            }

            return $output;
        },
    ],

    [
        'attribute' => 'otherwise2',
        'visible'=>\app\models\Flight::isVisibleAttr('otherwise2'),
    ],

    [
        'attribute' => 'salary',
        'visible'=>\app\models\Flight::isVisibleAttr('salary'),
        'label' => 'Зарплата',
        'content' => function($model){
            if(\Yii::$app->user->identity->isSuperAdmin()){
                return \app\widgets\AjaxInput::widget([
                    'model' => $model,
                    'format' => ['integer'],
                    'attr' => 'salary',
                ]);
            } else {
                return \Yii::$app->formatter->asInteger($model->salary).' руб.';
            }
        },
        'format' => ['decimal', 2],
    ],


    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrier_id',
        'visible'=>\app\models\Flight::isVisibleAttr('carrier_id'),
        'filter' => '',
        'contentOptions' => function($model){
            if($model->is_ati_driver){
                return ['class' => 'success'];
            }
        },
        'label' => 'АТИ Водителя',
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->carrier_id);

            if($carrier){
                return \yii\helpers\Html::a($carrier->code, "https://ati.su/firms/{$carrier->code}/rating", ['data-pjax' => 0, 'target' => '_blank']).'<br>'.Html::a($carrier->email, 'mailto:'.$carrier->email, ['data-pjax' => 0, 'target' => '_blank']).'<br>'.'<input type="checkbox" data-id="'.$model->id.'" data-e="is_ati_driver" '.($model->is_ati_driver ? 'checked="true"' : '').'">';
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
        // 'attribute'=>'organization_id',
        'visible'=>\app\models\Flight::isVisibleAttr('organization_id'),
        'label' => 'АТИ Заказчика',
        // 'headerOptions' => ['style' => 'width: 10px;'],
        'width' => '10px',
        'contentOptions' => function($model){
            if($model->is_ati_client){
                return ['class' => 'success'];
            }
        },
        'content' => function($model){
            $carrier = \app\models\Client::findOne($model->zakazchik_id);



            if($carrier){
                return \yii\helpers\Html::a($carrier->code, "https://ati.su/firms/{$carrier->code}/rating", ['data-pjax' => 0, 'target' => '_blank']).'<br>'.Html::a($carrier->email, 'mailto:'.$carrier->email, ['data-pjax' => 0, 'target' => '_blank']).'<br>'.'<input type="checkbox" data-id="'.$model->id.'" data-e="is_ati_client" '.($model->is_ati_client ? 'checked="true"' : '').'">';
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
        // 'pageSummary' => true, 
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

