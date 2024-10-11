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
/* @var $searchModel LoginConnectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Входы";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['login-connect/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['login-connect/create'];
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


<div class="col-md-12">
    <div class="card card-shadow m-b-10">
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => [Yii::$app->controller->id.'/index']]) ?>

                <div class="col-md-12">

                <?= $form->field($searchModel, 'login', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->textInput() ?>

                <?= $form->field($searchModel, 'status', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->textInput() ?>

                <?= $form->field($searchModel, 'ip_address', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false,])->textInput() ?>


                </div>				

                <div class="col-md-12">
                    <hr style="margin-top: 5px; margin-bottom: 15px;">
                </div>

                <div class="col-md-12">
                    <div style="float: right;">
                        <?= Html::a('Сбросить', ['login-connect/index'], ['class' => 'btn btn-white']) ?>
                        <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="row">
            <div class="col-md-12">

                    <div class="card card-columns">
                            <div class="card-column" style="">

                                    <div class="card-box">
                                            <div><input type="checkbox" id="check_all"></div>


                                            <div class="padding-0-5">
    <?php

    if(((\Yii::$app->user->identity->isSuperAdmin() || (\Yii::$app->user->identity->isManager())) && \Yii::$app->user->identity->can('flight_btn_delete')) || 
    \Yii::$app->user->identity->can('flight_btn_permament_delete')){

            //<button type="button" data-id="'.$model->id.'" val="'.($model->is_signature ? '' : '1').'" data-e="is_signature" class="btn_is_signature btn '.($model->is_signature ? 'btn-primary' : 'btn-secondary').'">Подписано</button>
            /*
            echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['flight/bulk-delete', 'pks' => '111,333'], [
                            'id' => "delete_checks",
                            'class' => 'btn btn-danger',
                            'role'=>'modal-remote','title'=>'Удалить выделенные записи?', 
                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                            'data-request-method'=>'post',
                            'data-toggle'=>'tooltip',
                            'data-confirm-title'=>'Вы уверены?',
                            'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию?'
            ]);
            */
            echo Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                    'id' => "delete_checks",
                    'class' => 'btn btn-danger',
            ]);



    }
            ?>
                                            </div>

                                    </div>

                            </div>	



                    </div>

            </div>

    </div>
</div>


<div class="col-md-12">



<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-login_connect',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'id',
    'titleAttribute' => 'ip_address',
    'listOptions' => [
        'style' => 'height: 200px; overflow-y: scroll;',
    ],
    'list' => [
        'status',
        'login',
        'code',
    ],
    'buttonsTemplate' => '{delete}{checkbox}',
    //'buttonsTemplate' => '{delete} {update}',
    'buttons' => [
        
        'checkbox'=> function($model){
    
            return '
                <div>
                        <label class="container_checkbox">
                                <input type="checkbox" class="check_items" name="check_items" data-id="'.$model->id.'" data-e="check_items" value="'.$model->id.'">
                                <span class="checkmark"></span>
                        </label>
                </div>';
					
    
        },
        
        'update' => function($model){
            return Html::a('<i class="fa fa-pencil"></i>', ['login-connect/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            return Html::a('<i class="fa fa-trash"></i>', ['login-connect/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
                            'class' => 'btn btn-sm btn-white', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию']);
        },
    ],
]) ?>
 
<script>
    function convert_to_float(a) {
        var floatValue = +(a);
        return floatValue;
    }
    function maxLengthCheck(object)
    {
        if (convert_to_float(object.value) > convert_to_float(object.max)){
          object.value = object.max
        }
    }
    
    $('#delete_checks').click(function(){

            if (window.confirm("Вы действительно хотите удалить выбранные записи?")) {
                    var pks = $(this).attr('pks');

                    $.post('/login-connect/bulk-delete', { pks: pks }, function(response){
                            console.log(response, 'response');
                            $.pjax.reload({container: "#crud-datatable-login_connect-pjax"});
                    });	

            }
    });    
    
    function update_pks(){

            var ids = [];
            $('.check_items:checkbox:checked').each(function () {
                    ids.push($(this).val());
            });

            $('#delete_checks').attr('pks',ids.join(','));

    }


    $('.check_items').change(function(){
            update_pks();
    });



    $('#check_all').change(function(){

            //var value = $(this).is(':checked') ? 1 : 0;	

            if($(this).is(':checked')){
                    $(".check_items").prop( "checked", true );
            }else{
                    $(".check_items").prop( "checked", false );
            }

            update_pks();

    });    
    
    
    
    
</script>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "options" => [ 
        "tabindex" => false, 
    ], 
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
