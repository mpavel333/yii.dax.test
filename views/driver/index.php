
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

$this->title = "Водители";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['driver/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['driver/create'];
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
          <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['driver/create'],
                          ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
  </div>


    <div class="col-md-12">
        <div class="card card-shadow m-b-10">
            <div class="row">
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['driver/index']]) ?>

                    <?= $form->field($searchModel, 'data', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'driver', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'phone', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'data_avto', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>


                    <div class="col-md-12">
                        <hr style="margin-top: 5px; margin-bottom: 15px;">
                    </div>

                    <div class="col-md-12">
                        <div style="float: right;">
                            <?= Html::a('Сбросить', ['driver/index'], ['class' => 'btn btn-white']) ?>
                            <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>



                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>


<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-driver',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'id',
    'titleAttribute' => 'data',
    'listOptions' => [
        'style' => 'height: 250px; overflow-y: scroll;',
    ],
    'list' => [
        'driver',
        'phone',
        'data_avto',
        'snils',
        [
            'attribute' => 'file',
            'label' => 'Файл',
            'content' => function($model){
                $files = json_decode($model->file);
                $out='';
                foreach($files as $file){
                    if(isset($file->url)){
                        $out .= Html::a($file->name, [$file->url], ['target'=>'_blank','role' => '', 'title' => '', 'class' => 'btn btn-sm btn-white']);
                    }
                }
                return $out;
            },
          
        ],
    ],
    'buttonsTemplate' => '{delete} {update}',
    'buttons' => [
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['driver/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['driver/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
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
