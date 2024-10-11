<?php
use app\models\Template;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

return [
//    [
//        'class' => 'kartik\grid\CheckboxColumn',
//        'width' => '20px',
//    ],
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
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{update}{delete}',
        'buttons' => [
            'delete' => function ($url, $model) {
                return Html::a('<i class="fa fa-trash text-danger" style="font-size: 16px;"></i>', $url, [
                    'role'=>'modal-remote', 'title'=>'Удалить',
                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    'data-request-method'=>'post',
                    'data-confirm-title'=>'Вы уверены?',
                    'data-confirm-message'=>'Вы действительно хотите удалить данную запись?'
                ]);
            },
            'update' => function ($url, $model) {
                return Html::a('<i class="fa fa-pencil text-primary" style="font-size: 16px;"></i>', $url, [
                    'role'=>'modal-remote', 'title'=>'Изменить',
                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    'data-request-method'=>'post',
                ])."&nbsp;";
            }
        ],
    ],

];   