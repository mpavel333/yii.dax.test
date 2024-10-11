<?php
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
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$model->id]);
        },
        'template' => '',
        'buttons' => function(){
            'copy' => function($key, $model){
                return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['flight/copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
										'class' => 'btn btn-sm btn-white',
										'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
										'data-request-method'=>'post',
										'data-toggle'=>'tooltip',
										'data-confirm-title'=>'Вы уверены?',
										'data-confirm-message'=>'Вы уверены что хотите копировать эту позицию?']);
            },
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Просмотр','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Изменить', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Удалить',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Удаление',
                          'data-confirm-message'=>'Вы действительно хотите удалить данный элемент?'],
    ],

];   