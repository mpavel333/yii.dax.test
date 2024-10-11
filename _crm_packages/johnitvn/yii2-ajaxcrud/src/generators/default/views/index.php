<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
echo "<?php\n";
?>
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "<?= $generator->pageName ?>";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="panel panel-inverse <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div class="panel-heading">
<!--        <div class="panel-heading-btn">-->
<!--        </div>-->
        <h4 class="panel-title"><?= $generator->pageName ?></h4>
    </div>
    <div class="panel-body">
        <div id="ajaxCrudDatatable">
            <?="<?="?>GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
            ['content'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
            ['role'=>'modal-remote','title'=> 'Добавить <?=$generator->rusAccusativeCase?>','class'=>'btn btn-default']).
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
            ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Обновить']).
            '{toggleData}'.
            '{export}'
            ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
            'headingOptions' => ['style' => 'display: none;'],
            'after'=>BulkButtonWidget::widget([
            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Удалить',
            ["bulk-delete"] ,
            [
            "class"=>"btn btn-danger btn-xs",
            'role'=>'modal-remote-bulk',
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-confirm-title'=>'Вы уверены?',
            'data-confirm-message'=>'Вы действительно хотите удалить данный элемент?'
            ]),
            ]).
            '<div class="clearfix"></div>',
            ]
            ])<?="?>\n"?>
        </div>
    </div>
</div>

<?='<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>'."\n"?>
<?='<?php Modal::end(); ?>'?>

