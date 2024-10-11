<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Requisite */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'display:none;';
}
if($model->isNewRecord == false){
    $model->rows = \app\models\RequisiteEnsurance::find()->where(['requisite_id' => $model->id])->asArray()->all();
    for ($i=0; $i < count($model->rows); $i++) {
        $row = $model->rows[$i];
        foreach($row as $key => $value){
        }
    }
}

//$conditions = array_unique(ArrayHelper::getColumn(\app\models\RequisiteEnsurance::find()->where(['requisite_id' => $model->id])->asArray()->all(), 'condition'));
//$conditions = array_combine($conditions, $conditions);

//print_r($conditions);

?>
<style type="text/css">
    .panel-default .panel-heading {
        background: #ededed !important;
    }

    .panel-default .panel-body {
        padding: 10px !important;
        background: #f9f9f9 !important;
        border-right: 1px solid #e9e9e9;
        border-left: 1px solid #e9e9e9;
        border-bottom: 1px solid #e9e9e9;
    }

    #ajaxCrudModal .modal-dialog {
        width: 85% !important;
    }
</style>
<div class="requisite-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Основные</h4>
                </div>
                <div class="panel-body">
                    <div class="row">


<?= $form->field($model, 'name', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'doljnost_rukovoditelya', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'fio_polnostyu', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'official_address', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'bank_name', ['cols' => 4, 'colsOptionsStr' => " "])->widget(\kartik\typeahead\Typeahead::class, [
                                'dataset' => [
                                    [
                                        'local' => \yii\helpers\ArrayHelper::getColumn(\app\models\Bank::find()->all(), 'name'),
                                        'limit' => 10,
                                    ]
                                ],
                                'options' => [
                                    'autocomplete' => 'off',
                                ],
                             ])  ?>
                                    
                             <?= $form->field($model, 'inn', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'kpp', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'ogrn', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'bic', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'kr', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'nomer_rascheta', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'tel', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                    
                             <?= $form->field($model, 'fio_buhgaltera', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>

                             <?= $form->field($model, 'post_address', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>
                                  
                             <?= $form->field($model, 'card',['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>

                             <?= $form->field($model, 'nds',['cols' => 12, 'colsOptionsStr' => " "])->checkbox()  ?>

                             <?= $form->field($model, 'filePechat', ['cols' => 6, 'colsOptionsStr' => " "])->fileInput()  ?>

                             <?= $form->field($model, 'fileSignature', ['cols' => 6, 'colsOptionsStr' => " "])->fileInput()  ?>
                    
                             <?= $form->field($model, 'name_case', ['cols' => 4, 'colsOptionsStr' => " "])->textInput()  ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Реквизиты</h4>
                </div>
                <div class="panel-body">
                
                    <div class="row">
                                                               
                        <?= $form->field($model, 'main_bank_name', ['cols' => 12, 'colsOptionsStr' => " "])->widget(\kartik\typeahead\Typeahead::class, [
                           'dataset' => [
                               [
                                   'local' => \yii\helpers\ArrayHelper::getColumn(\app\models\Bank::find()->all(), 'name'),
                                   'limit' => 10,
                               ]
                           ],
                           'options' => [
                               'autocomplete' => 'off',
                           ],
                        ])  ?>
                               
                        <?= $form->field($model, 'main_ogrn', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                               
                        <?= $form->field($model, 'main_bic', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                               
                        <?= $form->field($model, 'main_kr', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                               
                        <?= $form->field($model, 'main_nomer_rascheta', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

                    </div>

                </div>
            </div>


        </div>
        <div class="col-md-4">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Дополнительные</h4>
                </div>
                <div class="panel-body">
                        <?= $form->field($model, 'add_bank_name', ['cols' => 12, 'colsOptionsStr' => " "])->widget(\kartik\typeahead\Typeahead::class, [
                           'dataset' => [
                               [
                                   'local' => \yii\helpers\ArrayHelper::getColumn(\app\models\Bank::find()->all(), 'name'),
                                   'limit' => 10,
                               ]
                           ],
                           'options' => [
                               'autocomplete' => 'off',
                           ],
                        ])  ?>
                               
                        <?= $form->field($model, 'add_ogrn', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                               
                        <?= $form->field($model, 'add_bic', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                               
                        <?= $form->field($model, 'add_kr', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                               
                        <?= $form->field($model, 'add_nomer_rascheta', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
                </div>
            </div>

        </div>

        <div class="col-md-12">
         <?= $form->field($model, 'rows', ['cols' => 12, 'colsOptionsStr' => " ", "checkPermission" => false])->widget(unclead\multipleinput\MultipleInput::className(), [
            'id' => 'requisite_ensurance',
            'columnClass' => app\base\BaseColumn::class,
            'min' => 0,
            'columns' => [
                    [
                        'name' => 'id',
                        'options' => [
                            'type' => 'hidden'
                        ]
                    ],
                    [
                        'name' => 'name',
                        'title' => Yii::t('app','Страховка'),
                        'enableError' => true,
                    ],        
                    [
                        'name' => 'contract',
                        'title' => Yii::t('app','Договор'),
                    ],

                    [
                        'name' => 'condition_select',
                        'type' => \kartik\select2\Select2::className(),
                        

                        'options' => [
                            'data' => [],
                             
                            'options' => [
                                'placeholder' => 'Выберите',
                            ],
                            'pluginOptions' => [
                                'tags' => true,
                                'allowClear' => true,
                            ],
                            'pluginEvents' => [

                                'change' => 'function(){ 
                                   
                                    var self = $(this).parent().parent().parent();
                                    var value = $(this).val(); 
                                   
                                    $.get("requisite/get-ensurance-conditions?value="+value, function(response){ 
                                        if(response.model){
                                            $(self).find(".list-cell__condition input").val(response.model.condition);
                                            $(self).find(".list-cell__percent input").val(response.model.percent);
                                        }
                                    }); 
                                 
                                }',
                            ],
                        ],
                        'title' => Yii::t('app','Условие/Сохр'),
                    ],
                
                    [
                        'name' => 'condition',
                        'title' => Yii::t('app','Условие'),
                    ],                
                    [
                        'name' => 'percent',
                        'options' => [
                            'type' => 'number',
                        ],
                        'title' => Yii::t('app','Процент'),
                    ],          
                ],
            ])->label(false)  ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<!-- <script src="/libs/jquery.maskedinput.min.js"></script> -->

<!-- <script>$("#client-phone").mask("+7 (999) 999-9999");</script>-->

<?php

$script = <<< JS
        
$('#requisite_ensurance').on('afterInit', function(){
    //console.log('calls on after initialization event');
}).on('beforeAddRow', function(e, row, currentIndex) {
    //console.log('calls on before add row event');
}).on('afterAddRow', function(e, row, currentIndex) {
    //console.log('calls on after add row event');
}).on('beforeDeleteRow', function(e, row, currentIndex){
   let result = confirm('Все сохраненные условия данной страховки будут удалены. Удалить запись?')
   let row_id = row.find('.list-cell__id input').val();

    if(result && row_id){
         $.get("requisite/delete-ensurance-conditions?value="+row_id, function(response){});        
    }else{
         return false;
    }
        
}).on('afterDeleteRow', function(e, row, currentIndex){
    //console.log('calls on after remove row event');
}).on('afterDropRow', function(e, item){       
    //console.log('calls on after drop row', item);
});
   

$('#requisite-bank_name').change(function(){
    var name = $(this).val();
    
    $.get('/requisite/find-bank?name='+name, function(response){
        if(response){
            $('#requisite-inn').val(response.inn);
            $('#requisite-kpp').val(response.kpp);
            $('#requisite-ogrn').val(response.ogrn);
            $('#requisite-bic').val(response.bik);
            $('#requisite-kr').val(response.kr);
            $('#requisite-nomer_rascheta').val(response.number);
        }
    });


});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>