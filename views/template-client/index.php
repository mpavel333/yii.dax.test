<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Шаблоны документов";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<style>
    .modal-slg .modal-dialog {
        width: 80%;
    }
</style>

<div class="row">
  <div class="col-md-12" style="margin-bottom: 10px;">
          <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['template-client/create'],
                          ['data-pjax' => true, 'title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
  </div>


</div>


<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'id',
    'titleAttribute' => 'name',
    'listOptions' => [
        'style' => 'height: 50px; overflow-y: scroll;',
    ],
    'list' => [
    ],
    'buttonsTemplate' => '{delete} {update}',
    'buttons' => [
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['template-client/update', 'id' => $model->id], ['title' => 'Редактировать', 'class' => 'btn btn-sm btn-white', 'data-pjax' => 0]);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['template-client/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
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
    'options' => ['class' => 'modal-slg'],
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
