<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkKindSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Роли";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<style>
    .modal-dialog {
        width: 80% !important;
    }
</style>

<div class="row">
  <div class="col-md-12" style="margin-bottom: 10px;">
          <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['role/create'],
                          ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
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
        'style' => 'height: 100px; overflow-y: scroll;',
    ],
    'list' => [
        'name',
    ],
    'buttonsTemplate' => '{copy} {delete} {update}',
    'buttons' => [
        'copy' => function($model){
            return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['role/copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
                                    'class' => 'btn btn-sm btn-white',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-toggle'=>'tooltip',
                                    'data-confirm-title'=>'Вы уверены?',
                                    'data-confirm-message'=>'Вы уверены что хотите копировать эту роль?']);
        },
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['role/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['role/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
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
    'options' => ['class' => 'fade modal-slg'],
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
