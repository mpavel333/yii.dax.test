<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\helpers\ArrayHelper;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel DriverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Структура компании";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
/*
if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['driver/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['driver/create'];
}
*/
?>

<style>
    #ajaxCrudDatatable .panel-info>.panel-heading {
        display: none!important;
    }
    #ajaxCrudDatatable .panel-info>.kv-panel-before>.pull-right {
        float: left!important;
    }
    #ajaxCrudDatatable .panel-info>.table-responsive {
        padding:  0px !important;
    }
</style>

<div class="row">
  <div class="col-md-12" style="margin-bottom: 10px;">
          <?php 
          if(Yii::$app->user->identity->can('structure_create')){
            echo Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['structure/create'],
                            ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']);
          } ?>
  </div>


    <div class="col-md-12">
        <div class="card card-shadow m-b-10">
            <div class="row">
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['structure/index']]) ?>

                    <?= $form->field($searchModel, 'name', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

         
                    <?= $form->field($searchModel, 'email', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'phone', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'tabel', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <div class="col-md-12">
                        <hr style="margin-top: 5px; margin-bottom: 15px;">
                    </div>

                    <div class="col-md-12">
                        <div style="float: right;">
                            <?= Html::a('Сбросить', ['structure/index'], ['class' => 'btn btn-white']) ?>
                            <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>



                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>


<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-structure',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'name',
    //'titleAttribute' => 'name',
    'listOptions' => [
        'style' => 'max-height: 250px; overflow-y: auto;',
    ],
    'list' => [
        
        [
            'label' => 'Пользователи',
            'class' => 'yii\grid\DataColumn',
            'content' => function ($data) {
                $out = '';
                foreach ($data->structureUsers as $user){
                    $out .= '<ul>';
                        $out .= '<li>Логин - '.$user->login.'</li>';
                        $out .= '<li>ФИО - '.$user->name.'</li>';
                        $out .= '<li>Почта - '.$user->email.'</li>';
                        $out .= '<li>Телефон - '.$user->phone.'</li>';
                        $out .= '<li>Табель - '.$user->tabel.'</li>';
                    $out .= '</ul>';
                    $out .= '<hr>';
                }
                return $out;
            }
        ],        
        
    ],
    /*
    'list' => [
        'login',
        'email',
        'login',
        'login',
        'login',
    ],
    */
    'buttonsTemplate' => '{delete} {update}',
    'buttons' => [
        'update' => function($model){
            if(Yii::$app->user->identity->can('structure_update')){
                return Html::a('<i class="fa fa-pencil"></i>', ['structure/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
            }
        },
        'delete' => function($model){
            if(Yii::$app->user->identity->can('structure_delete')){
                return Html::a('<i class="fa fa-trash"></i>', ['structure/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
                                'class' => 'btn btn-sm btn-white', 
                              'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                              'data-request-method'=>'post',
                              'data-toggle'=>'tooltip',
                              'data-confirm-title'=>'Вы уверены?',
                              'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию']);
            }
        },
    ],
]) ?>


<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
