<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\helpers\ArrayHelper;
use kartik\dynagrid\DynaGrid;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel RequisiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Реквизиты";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['requisite/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['requisite/create'];
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
        <?= Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', $createUrl,
                        ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']). '     '.Html::a(Yii::t('app', 'Импорт'), ['add'],
                            ['role'=>'modal-remote','title'=> 'Импорт', 'class'=>'btn btn-warning']) ?>
    </div>

    <div class="col-md-12">
        <div class="card card-shadow m-b-10">
            <div class="row">
                <?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['requisite/index']]) ?>

                    <?= $form->field($searchModel, 'name', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'doljnost_rukovoditelya', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'fio_polnostyu', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'tel', ['cols' => 3, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'official_address', ['cols' => 4, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'inn', ['cols' => 4, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <?= $form->field($searchModel, 'kpp', ['cols' => 4, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>

                    <div class="col-md-12">
                        <hr style="margin-top: 5px; margin-bottom: 15px;">
                    </div>

                    <div class="col-md-12">
                        <div style="float: right;">
                            <?= Html::a('Сбросить', ['requisite/index'], ['class' => 'btn btn-white']) ?>
                            <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-requisite',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'id',
    'titleAttribute' => 'name',
    'listOptions' => [
        'style' => 'height: 400px; overflow-y: scroll;',
    ],
    'list' => [
        'doljnost_rukovoditelya',
        'fio_polnostyu',
        'official_address',
        'bank_name',
        'inn',
        'kpp',
        'ogrn',
        'user.name',
        [
            'label' => 'Логин',
            'value' => 'user.login',
        ],
        [
            'label' => 'Кол-во рейсов',
            'value' => 'flightsCount',
            'format' => ['decimal', 0],
        ],
        [
            'label' => 'Свой контент',
            'content' => function($model){
                return \yii\helpers\Html::a($model->fio_polnostyu, '#');
            },
        ],
    ],
    'buttonsTemplate' => '{delete} {hide} {copy} {update}',
    'buttons' => [
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['requisite/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['requisite/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
                            'class' => 'btn btn-sm btn-white', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию']);
        },
        'copy' => function($model){
            return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['requisite/copy', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Копировать', 
                          'class' => 'btn btn-sm btn-white',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите копировать эту позицию']);
        },
        'hide' => function($model){
             return Html::a($model->is_hidden ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>', ['requisite/toggle-attribute', 'id' => $model->id, 'attr' => 'is_hidden'], ['class' => 'btn btn-sm btn-white', 'onclick' => 'event.preventDefault(); var self = this; $.get($(this).attr("href"), function(response){ if(response.result){ $(self).find("i").attr("class", "fa fa-eye-slash"); } else { $(self).find("i").attr("class", "fa fa-eye"); } })']);
        },
    ],
]) ?>
        
<?php
$script = <<< JS
$('[data-key]').dblclick(function(e){
    if($(e.target).is('td')){
        var id = $(this).data('key');
        window.location = '/requisite/view?id='+id;
    }
});

$(document).on('pjax:complete' , function(event) {
    $('[data-key]').dblclick(function(e){
        if($(e.target).is('td')){
            var id = $(this).data('key');
            window.location = '/requisite/view?id='+id;
        }
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'clientOptions' => [
        'backdrop' => 'static'
    ],    
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
