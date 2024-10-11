<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;

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
        'attribute'=>'login',
        'width' => '200px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'phone',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'role',
        'value' => function($model){
            // return ArrayHelper::getValue(User::roleLabels(), $model->role);
            return $model->role;
        }
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'is_deletable',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{update} {delete}',
        'buttons' => [
            'delete' => function ($url, $model) {
                return Html::a('<i class="fa fa-trash text-danger" style="font-size: 16px;"></i>', $url, [
                    'role'=>'modal-remote', 'title'=>'Delete',
                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    'data-request-method'=>'post',
                    'data-confirm-title'=>'Are you sure?',
                    'data-confirm-message'=>'Вы действительно хотите удалить данного пользователя?'
                ]);
            },
            'update' => function ($url, $model) {
                return Html::a('<i class="fa fa-pencil text-primary" style="font-size: 16px;"></i>', $url, [
                    'role'=>'modal-remote', 'title'=>'Update',
                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    'data-request-method'=>'post',
                ]);
            },
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Save', 'data-toggle'=>'tooltip'],
    ],

];   