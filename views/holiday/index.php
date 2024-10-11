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
/* @var $searchModel HolidaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Праздники";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['holiday/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['holiday/create'];
}
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
          <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['holiday/create'],
                          ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
  </div>


</div>


<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-holiday',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'id',
    'titleAttribute' => 'date',
    'listOptions' => [
        'style' => 'height: 50px; overflow-y: scroll;',
    ],
    'list' => [
    ],
    'buttonsTemplate' => '{delete} {update}',
    'buttons' => [
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['holiday/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['holiday/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
                            'class' => 'btn btn-sm btn-white', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию']);
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

