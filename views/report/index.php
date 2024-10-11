<?php

use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\ReportColumn;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

/** @var $columns array */

\johnitvn\ajaxcrud\CrudAsset::register($this);

$this->title = 'Отчет';

$data = [];

$query = ReportColumn::find();

//if(Yii::$app->user->identity->isSuperAdmin() == false){
//    $query->andWhere(['user_id' => Yii::$app->user->getId()]);
//}

$data = ArrayHelper::map($query->all(), 'id', 'name');

$reportColumn = new \app\components\ComponentReport(['columns' => $columns]);
$columns = $reportColumn->getGridColumns();

$report = ReportColumn::findOne($searchModel->setting);

$exportUrl = ['report/export-excel'];

$exportUrl = ArrayHelper::merge($exportUrl, Yii::$app->request->queryParams);

?>

<style>
    table {
        font-size: 11px;

    }

    .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
        padding: 2px !important;
    }

    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        border-color: #e2e7eb;
        padding: 2px;
        background: #fff;
    }
    .modal-dialog {
        width: 80% !important;
    }
</style>

<?php Pjax::begin(['id' => 'report-pjax-container']) ?>
<div class="panel panel-inverse position-index">
    <div class="panel-heading">
        <!--        <div class="panel-heading-btn">-->
        <!--        </div>-->
        <h4 class="panel-title">Отчет</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">

                <?php

                $search = $this->render('_search', [
                    'searchModel' => $searchModel,
                ]);

                ?>

            </div>
        </div>

            <div id="ajaxCrudDatatable">
                <?=GridView::widget([
                    'id'=>'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    // 'pjax'=>true,
                    'columns' => $columns,
                    'panelBeforeTemplate' => $search,
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'panel' => [
                        'headingOptions' => ['style' => 'display: none;'],
                        'after'=>'',
                    ]
                ])?>
            </div>
    </div>
</div>
<?php Pjax::end() ?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'options' => ['class' => 'fade modal-slg'],
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
