
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
                return Url::to(["client"."/".$action,'id'=>$key]);
        },
        'template' => '{print} {view} {update} {delete}',
        'buttons' => [
            'print' => function($url, $model){
                return Html::a('<span class="glyphicon glyphicon-print"></span>', ['client/print-from-client', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Печать']);
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
        'attribute' => 'organization_id',
        'visible'=>\app\models\Flight::isVisibleAttr('organization_id'),
        'content' => function($model){
            $requisiteData = [];
            foreach (\app\models\Requisite::find()->where(['is_hidden' => false])->all() as $requisite) {
                $requisiteData[$requisite->id] = $requisite->name." ({$requisite->inn}) ".$requisite->bank_name;
            }

            return Html::dropDownList('status'.$model->id, $model->organization_id, $requisiteData, [
                    'class' => 'form-control',
                    'prompt' => 'Выберите',
                    'onchange' => '$.get("/client/update-attr?id='.$model->id.'&attr=organization_id&value="+$(this).val());',
                ]);

        },
    ],

        [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'name',
         'visible'=>\app\models\Client::isVisibleAttr('name'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'doljnost_rukovoditelya',
         'visible'=>\app\models\Client::isVisibleAttr('doljnost_rukovoditelya'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'fio_polnostyu',
         'visible'=>\app\models\Client::isVisibleAttr('fio_polnostyu'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'contact',
         'visible'=>\app\models\Client::isVisibleAttr('contact'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'official_address',
         'visible'=>\app\models\Client::isVisibleAttr('official_address'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'bank_name',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'inn',
         'visible'=>\app\models\Client::isVisibleAttr('inn'),
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
         'attribute'=>'email',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'nds',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'doc',
         'visible'=>\app\models\Client::isVisibleAttr('doc'),
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'mailing_address',
         'visible'=>0,
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'code',
         'visible'=>0,
    ],

];   

