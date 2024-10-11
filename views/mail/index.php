<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\helpers\ArrayHelper;
use kartik\dynagrid\DynaGrid;
use app\models\Mail;

/* @var $this yii\web\View */
/* @var $searchModel MailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Почта | Исходящие";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['mail/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['mail/create'];
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
          <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['mail/create'],
                          ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']) ?>
  </div>


    <div class="col-md-12">
        <div class="card card-shadow m-b-10">
            <div class="row">
                <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['mail/index']]) ?>

                    <?= $form->field($searchModel, 'organization_name', ['cols' => 4, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>


                    <?= $form->field($searchModel, 'user_id', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->widget(\kartik\select2\Select2::class, [
						'data' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'),
						'options' => ['prompt' => 'Выбрать', 'multiple' => false],
						'pluginOptions' => [
								'allowClear' => true,
								'tags' => false,
								'tokenSeparators' => [','],
						]
					]) ?>

                    <?= $form->field($searchModel, 'information', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'track', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'when_send', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>


                    <div class="col-md-12">
                        <hr style="margin-top: 5px; margin-bottom: 15px;">
                    </div>

                    <div class="col-md-12">
                        <div style="float: right;">
                            <?= Html::a('Сбросить', ['mail/index'], ['class' => 'btn btn-white']) ?>
                            <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>



                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>


<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-mail',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'id',
    'titleAttribute' => 'organization_name',
    'listOptions' => [
        'style' => 'height: 200px; overflow-y: scroll;',
    ],
    'list' => [
        [
            'label' => 'ФИО',
            'attribute'=>'user_id',
            'value'=>'user.name',
        ],
        'information',
        'upd',
        'track',
        'when_send',
    ],
    'buttonsTemplate' => '{delete} {update}',
    'buttons' => [
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['mail/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['mail/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
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


<?php 
$script = <<< JS

    $( function() {
        $( "#mailsearch-when_send" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });

JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?>