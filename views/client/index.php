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
/* @var $searchModel ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Организации";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

if(isset($additionalLinkParams)){
    $createUrl = ArrayHelper::merge(['client/create'], $additionalLinkParams);
    $createUrl = ArrayHelper::merge($createUrl, ['display' => false]);
} else {
    $createUrl = ['client/create'];
}

?>
<style>
    .modal-dialog {
        width: 80% !important;
    }
</style>


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
      <?php if(Yii::$app->user->identity->can('client_create')){
           echo Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['client/create'],
                          ['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']);
      } ?>
  </div>


    <div class="col-md-12">
        <div class="card card-shadow m-b-10">
            <div class="row">
                <?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => ['client/index']]) ?>

                    <?= $form->field($searchModel, 'name', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?php /*
                    <?= $form->field($searchModel, 'doljnost_rukovoditelya', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'fio_polnostyu', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'contact', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'official_address', ['cols' => 2, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    */ ?>
                    <?= $form->field($searchModel, 'inn', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>
                    <?= $form->field($searchModel, 'doc', ['cols' => 1, 'colsOptionsStr' => " ", 'checkPermission' => false])->textInput()  ?>


                    <div class="col-md-12">
                        <hr style="margin-top: 5px; margin-bottom: 15px;">
                    </div>

                    <div class="col-md-12">
                        
                        <div class="row">
                        
                            <div class="col-md-6">
                        
                                <div>

                                  <label for="contactChoice1">
                                      <span class="num-shipment color-yellow"></span>
                                      <input type="radio" id="contractChoice1" name="ClientSearch[contract]" value="1" />
                                  </label>


                                  <label for="contactChoice2">
                                      <span class="num-shipment color-red"></span>
                                      <input type="radio" id="contractChoice0" name="ClientSearch[contract]" value="0" />
                                  </label>

                                  <label for="contactChoice2">
                                      <span class="num-shipment color-green"></span>
                                      <input type="radio" id="contractChoice2" name="ClientSearch[contract]" value="2" />
                                  </label>
                                    
                                    <script type="text/javascript">
                                        document.getElementById("contractChoice<?=$searchModel->contract ?>").checked = true;
                                    </script> 

                                </div>                                
                               
                            </div>
                            
                            <div class="col-md-6">
                                <div style="float: right;">
                                    <?= Html::a('Сбросить', ['client/index'], ['class' => 'btn btn-white']) ?>
                                    <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
                                </div>
                            </div>
                            
                        </div>    
                    </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
<?php

?>

<?= \app\widgets\CardGrid::widget([
    'id'=>'crud-datatable-client',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'colSize' => 3,
    'serialAttribute' => 'id',
    'titleAttribute' => function ($data) {

        if($data->contract == 1 && $data->contract_orig == 1){
            $class = 'num-shipment color-green';
        }elseif($data->contract == 1){
            $class = 'num-shipment color-yellow';
        }elseif($data->contract_orig == 1){
            $class = 'num-shipment color-green';
        }else{
            $class = 'num-shipment';
        }

        return '<div class="titleAttribute"><span class="name">'.$data->name.'</span><span><span class="'.$class.'"></span></span></div>';

    },
    'listOptions' => [
        'style' => 'height: 200px; overflow-y: scroll;',
    ],
    'list' => [
        [
            'label' => 'Наша организация',
            'content' => function($model){
                $requisiteData = [];
                foreach (\app\models\Requisite::find()->where(['is_hidden' => false])->all() as $requisite) {
                    $requisiteData[$requisite->id] = $requisite->name." ({$requisite->inn}) ".$requisite->bank_name;
                }
    
                return Html::dropDownList('status'.$model->id, $model->organization_id, $requisiteData, [
                    'class' => 'form-control',
                    'prompt' => 'Выберите',
                    'onchange' => '$.get("/client/update-attr?id='.$model->id.'&attr=organization_id&value="+$(this).val());',
                ]);
            },
        ],
        'name',
        'inn',
        'official_address',
        /*     
        'email',
        [
            'label' => 'Договор',
            'class' => 'yii\grid\DataColumn',
            'content' => function ($data) {
                if($data->contract):
                    $class = 'fa fa-check text-success';
                else:
                    $class = 'fa fa-times text-danger';
                endif;
                return '<i class="'.$class.'" style="font-size: 16px;"></i>';
            }
        ],
        [
            'label' => 'Диспетчер',
            'class' => 'yii\grid\DataColumn',
            'content' => function ($data) {
                return \yii\helpers\ArrayHelper::getValue($data, 'user.name');
            },
        ],
         
         */
    ],
    'buttonsTemplate' => '{tabel} {lawyer} {update-files} {print} {delete} {update}',
    'buttons' => [
        
        'tabel' => function($model){
            if(Yii::$app->user->identity->can('client_contract')){
                return '<span>'.$model->user->name.'</span> <span>Табель:</span> '.Html::input('text', 'tabel', $model->tabel, ['class' => 'tabel','onchange'=>"updateAjaxAttr(this);",'data-id'=>$model->id,'data-e'=>"tabel"]);
            }else{
                return '<span>'.$model->user->name.'</span> <span>Табель:</span> '.Html::input('text', 'tabel', $model->tabel, ['class' => 'tabel','onchange'=>"updateAjaxAttr(this);",'data-id'=>$model->id,'data-e'=>"tabel", 'disabled'=>"disabled"]);
            }
            //return Html::a('<span class="glyphicon glyphicon-print"></span>', ['client/print-from-client', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Печать', 'class' => 'btn btn-sm btn-white']);
        },
        
        'update-files' => function($model){
            if(Yii::$app->user->identity->can('client_update')){
                return Html::a('<span class="glyphicon glyphicon-file"></span>', ['client/update-files', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Загрузка файлов', 'class' => 'btn btn-sm btn-white']);
            }
        },        

        'print' => function($model){
            return Html::a('<span class="glyphicon glyphicon-print"></span>', ['client/print-from-client', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Печать', 'class' => 'btn btn-sm btn-white']);
        },

        'update' => function($model){
            if($model->user_id==Yii::$app->user->getId() or Yii::$app->user->identity->isSuperAdmin() or Yii::$app->user->identity->can('client_update'))
                return Html::a('<i class="fa fa-pencil"></i>', ['client/update', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Редактировать', 'class' => 'btn btn-sm btn-white']);
        },
        'delete' => function($model){
            if(($model->user_id==Yii::$app->user->getId() or Yii::$app->user->identity->isSuperAdmin()) and ($model->contract == 0 and $model->contract_orig == 0 and Yii::$app->user->identity->can('client_delete')))
            return Html::a('<i class="fa fa-trash"></i>', ['client/delete', 'id' => $model->id], ['role'=>'modal-remote','title'=>'Удалить',
                            'class' => 'btn btn-sm btn-white', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Вы уверены?',
                          'data-confirm-message'=>'Вы уверены что хотите удалить эту позицию']);
        },

        'lawyer' => function($model){
							
            return '<button type="button" onclick="updateAjaxAttr(this);" data-id="'.$model->id.'" val="'.($model->is_lawyer ? '' : '1').'" data-e="is_lawyer" class="btn_is_lawyer min-btn '.($model->is_lawyer ? 'btn-warning' : 'btn-danger').'" title="'.($model->is_lawyer ? 'В работе' : '').'">'.($model->is_lawyer ? 'Юрист' : 'Юрист').'</button>';

        },	

    ],
]) ?>

<?php //print_r($widget); //->content; ?>



<?php 
$script = <<< JS

    window.updateAjaxAttr = function(element){

    var element = $(element);
    var attr = element.data('e');
    var id = element.data('id');
    
    var type = element.attr('type');
    if(type=='text'){
        var value = element.val();
    }else{
        var value = element.attr('val');
    }
 
    $.get('/client/update-ajax?id='+id+'&attribute='+attr+'&value='+value, function(response){
        //console.log(response, 'response');
        $.pjax.reload({
            container: "#crud-datatable-client-pjax",
            //url: '/flight',
            timeout: 0,
        });
    }); 

    }	       
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
