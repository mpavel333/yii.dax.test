<?php

use app\models\Call;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
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
        'attribute'=>'status',
        'content' => function($model){
            return Html::dropDownList('status'.$model->id, $model->status, Call::statusLabels(), [
                'class' => 'form-control',
                'prompt' => 'Выберите',
                'onchange' => '$.get("/call/update-attr?id='.$model->id.'&attr=status&value="+$(this).val());',
            ]);
        },
        'visible' => \Yii::$app->user->identity->isSuperAdmin(),
    ],
    [
        'attribute' => 'user_id',
        'label' => 'Менеджер',
        'content' => function($model){
            $userName = null;
            $user = \app\models\User::findOne($model->user_id);

            if($user){
                $userName = $user->name;
            }

            // if(\Yii::$app->user->identity->can('flight_manager_change')){
            if(\Yii::$app->user->identity->isSuperAdmin()){
                return Html::dropDownList('status'.$model->id, $model->user_id, \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'), [
                        'class' => 'form-control',
                        'prompt' => 'Выберите',
                        'onchange' => '$.get("/call/update-attr?id='.$model->id.'&attr=user_id&value="+$(this).val());',
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
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'phone1',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'phone2',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'status',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'inn',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'site',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'industry',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'city',
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'contact_name',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'timezone',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'result',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'post',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'client_id',
        'value'=>'client.name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'phone',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'files',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'user_id',
    //     'value' => 'user.name',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'create_at',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => \Yii::$app->user->identity->isSuperAdmin() ? '{view}{update}{delete}' : '{view}{update}',
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить данную запись?'], 
    ],

];   