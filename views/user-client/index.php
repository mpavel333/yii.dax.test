<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Пользователи клиента";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="row">
  <div class="col-md-12" style="margin-bottom: 10px;">
          <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['user-client/create'],
                          ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
  </div>


    <div class="col-md-12">
        <div class="card card-shadow m-b-10">
            <div class="row">
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['user-client/index']]) ?>

                    <?= $form->field($searchModel, 'login', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'name', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'role', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'phone', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <div class="col-md-12">
                        <hr style="margin-top: 5px; margin-bottom: 15px;">
                    </div>

                    <div class="col-md-12">
                        <div style="float: right;">
                            <?= Html::a('Сбросить', ['user-client/index'], ['class' => 'btn btn-white']) ?>
                            <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>

                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
        </div>
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
        'style' => 'height: 200px; overflow-y: scroll;',
    ],
    'list' => [
        'login',
        'name',
        'phone',
        'role',
    ],
    'buttonsTemplate' => '{delete} {update}',
    'buttons' => [
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['user-client/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['user-client/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
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
