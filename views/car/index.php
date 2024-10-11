<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('app', 'Автопарк');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<style>
    .panel-danger .panel-heading {
        display: none !important;
    }

    .btn-toolbar.kv-grid-toolbar {
        display: none;
    } 
    
    .modal-dialog {
        width: 80% !important;
    }

    [data-class="danger"] td {
        background: #ffdedd !important;
        border-color: #ffbdbc !important;
    }
</style>


<div class="row">
  <div class="col-md-12" style="margin-bottom: 10px;">
          <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', [\Yii::$app->controller->id.'/create'],
                          ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
  </div>


    <div class="col-md-12">
        <div class="card card-shadow m-b-10">
            <div class="row">
                <?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['car/index']]) ?>

                    <?= $form->field($searchModel, 'name', ['cols' => 4, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'number', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'status', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->dropDownList(\app\models\Car::statusLabels(), ['prompt' => 'Выберите'])  ?>
                    <?= $form->field($searchModel, 'driver_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Driver::find()->all(), 'id', 'data'), ['prompt' => 'Выберите'])  ?>
                    <?= $form->field($searchModel, 'mileage', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'user_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
						'data' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'),
						'options' => ['prompt' => 'Выбрать', 'multiple' => false],
						'pluginOptions' => [
								'allowClear' => true,
								'tags' => false,
								'tokenSeparators' => [','],
						]
					]) ?>

                    <?= $form->field($searchModel, 'driver_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
						'data' => \yii\helpers\ArrayHelper::map(\app\models\Driver::find()->all(), 'id', 'data'),
						'options' => ['prompt' => 'Выбрать', 'multiple' => false],
						'pluginOptions' => [
								'allowClear' => true,
								'tags' => false,
								'tokenSeparators' => [','],
						]
					]) ?>


                    <div class="col-md-12">
                        <hr style="margin-top: 5px; margin-bottom: 15px;">
                    </div>

                    <div class="col-md-12">
                        <div style="float: right;">
                            <?= Html::a('Сбросить', ['car/index'], ['class' => 'btn btn-white']) ?>
                            <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>



                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>


<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 2,
    'serialAttribute' => 'id',
    'titleAttribute' => 'name',
    'listOptions' => [
        'style' => 'height: 220px; overflow-y: scroll;',
    ],
    'list' => [
        'number',
        [
            'attribute' => 'status',
            'label' => 'Статус',
            'value' => function($model){
                return \yii\helpers\ArrayHelper::getValue(\app\models\Car::statusLabels(), $model->status);
            },
        ],
        [
            'attribute'=>'driver_id',
            'label' => 'Водитель',
            'value' => 'driver.data',
        ],
        'mileage',
        [
            'attribute' => 'user_id',
            'label' => 'Диспетчер',
            'value' => function($model){
                return \yii\helpers\ArrayHelper::getValue($model, 'user.name');
            },
        ],
    ],
    'buttonsTemplate' => '{print} {delete} {update}',
    'buttons' => [

        'print' => function($model){
            //if((($model->status == 3) || (\Yii::$app->user->identity->isClient() == false) || \Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->isSuperAdmin()) && \Yii::$app->user->identity->can('flight_btn_print')){
            //    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['car/print', 'id' => $model->id], ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Печать']);
            //}

            return Html::a('<i class="fa fa-print"></i>', ['car/print', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Печать', 'class' => 'btn btn-sm btn-white']);

        },

        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['car/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['car/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
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
