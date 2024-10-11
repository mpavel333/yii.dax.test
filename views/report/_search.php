<?php

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\ReportColumn;
use yii\helpers\Html;


$query = ReportColumn::find();

$data = ArrayHelper::map($query->all(), 'id', 'name');

$exportUrl = ['report/export-excel'];

$exportUrl = ArrayHelper::merge($exportUrl, Yii::$app->request->queryParams);

?>


<?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET']) ?>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($searchModel, 'setting')->widget(\kartik\select2\Select2::class, [
                'data' => $data,
            ])->label("Название отчета") ?>
        </div>
        <div class="col-md-6">
            <?= Html::a('<i class="fa fa-plus"></i>', ['report-column/create', 'reloadPjaxContainer' => '#report-pjax-container'], ['class' => 'btn btn-primary', 'role' => 'modal-remote', 'style' => 'margin-top: 22px;']) ?>
            <?= Html::a('<i class="fa fa-pencil"></i>', ['report-column/update', 'id' => $searchModel->setting, 'reloadPjaxContainer' => '#report-pjax-container'], ['class' => 'btn btn-primary', 'role' => 'modal-remote', 'style' => 'margin-top: 22px;']) ?>
            <?= Html::a('<i class="fa fa-trash"></i>', ['report-column/delete', 'id' => $searchModel->setting, 'reloadPjaxContainer' => '#report-pjax-container'], ['class' => 'btn btn-danger',
                'role' => 'modal-remote', 'style' => 'margin-top: 22px;',
                'title'=>'Удалить',
                'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                'data-request-method'=>'post',
                'data-confirm-title'=>'Удаление',
                'data-confirm-message'=>'Вы действительно хотите удалить данную запись?'
            ]) ?>
            <?= Html::submitButton('Применить', ['class' => 'btn btn-success', 'style' => 'margin-top: 22px;']) ?>
            <?= Html::a('Экспорт', $exportUrl, ['class' => 'btn btn-warning', 'title' => 'Экспортировать в Excel', 'data-pjax' => 0, 'style' => 'margin-top: 22px;']) ?>
        </div>
    </div>

<?php ActiveForm::end() ?>