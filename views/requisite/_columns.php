
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
                return Url::to(["requisite"."/".$action,'id'=>$key]);
        },
        'template' => '{hide} {update} {delete} {copy}',
        'buttons' => [
            'copy' => function($url, $model){
                return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['requisite/copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите копировать эту позицию']);
            },
            'hide' => function($url, $model){
                return Html::a($model->is_hidden ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>', ['requisite/toggle-attribute', 'id' => $model->id, 'attr' => 'is_hidden'], ['onclick' => 'event.preventDefault(); var self = this; $.get($(this).attr("href"), function(response){ if(response.result){ $(self).find("i").attr("class", "fa fa-eye-slash"); } else { $(self).find("i").attr("class", "fa fa-eye"); } })']);
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
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'name',
         'visible'=>\app\models\Requisite::isVisibleAttr('name'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'doljnost_rukovoditelya',
         'visible'=>\app\models\Requisite::isVisibleAttr('doljnost_rukovoditelya'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'fio_polnostyu',
         'visible'=>\app\models\Requisite::isVisibleAttr('fio_polnostyu'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'official_address',
         'visible'=>\app\models\Requisite::isVisibleAttr('official_address'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'bank_name',
         'visible'=>\app\models\Requisite::isVisibleAttr('bank_name'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'inn',
         'visible'=>\app\models\Requisite::isVisibleAttr('inn'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'kpp',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'ogrn',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'bic',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'kr',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'nomer_rascheta',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'tel',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'fio_buhgaltera',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'nds',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'pechat',
         'visible'=>0,
    ],

];   

