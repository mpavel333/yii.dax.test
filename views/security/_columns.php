
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
            return Url::to(["security"."/".$action,'id'=>$key]);
    },
   
    'viewOptions'=>['data-pjax'=>'0','title'=>'Просмотр','data-toggle'=>'tooltip', 'style' => (\Yii::$app->user->identity->can('security_view') == false ? "display: none;" : null)],
    'updateOptions'=>['role'=>'modal-remote','title'=>'Изменить', 'data-toggle'=>'tooltip', 'style' => (\Yii::$app->user->identity->can('security_update') == false ? "display: none;" : null)],
    'deleteOptions'=>['role'=>'modal-remote','title'=>'Удалить', 
                      'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                      'data-request-method'=>'post',
                      'data-toggle'=>'tooltip',
                      'data-confirm-title'=>'Вы уверены?',
                      'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию', 'style' => (\Yii::$app->user->identity->can('security_delete') == false ? "display: none;" : null)], 
    ],
   
   
 
        [
        'class'=>'\kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'token',
        'visible'=>\app\models\Security::isVisibleAttr('token'),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'admin_id',
        'visible'=>\app\models\Security::isVisibleAttr('admin_id'),
    ],

];   

