<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\Flight */
/* @var $form yii\widgets\ActiveForm */

$displayNone = '';
if (isset($_GET['display'])){
    $displayNone = 'display:none;';
}
if($model->isNewRecord == false){
}
?>

<style type="text/css">
    .mfp-bg {
        z-index: 10042 !important;
    }

    .mfp-wrap {
        z-index: 10043 !important;
    }

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
</style>
<div class="flight-form">

    <div class="panel panel-inverse">
        <div class="panel-heading"></div>
        <div class="panel-body">
            
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
                                               

        <?php
        $requisiteData = [];
        foreach (\app\models\Requisite::find()->all() as $requisite) {
            $requisiteData[$requisite->id] = $requisite->name." ({$requisite->inn})";
        }

        ?>                    

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <!--        <div class="panel-heading-btn">-->
                    <!--        </div>-->
                    <h4 class="panel-title">Контрагенты</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                         <?= $form->field($model, 'organization_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
                                    'data' => $requisiteData,
                                    'options' => [
                                        'placeholder' => 'Выберите',
                                        'disabled' => true,
                                    ],
                                ]) ?>
                                    
                            <?php
                            $clientData = [];
                            foreach (\app\models\Client::find()->where(['user_id' => Yii::$app->user->getId()])->all() as $client) {
                                $clientData[$client->id] = $client->name." ({$client->inn})";
                            }

                            ?>         
                         <?= $form->field($model, 'zakazchik_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
                                    'data' => $clientData,
                                    'options' => [
                                        'placeholder' => 'Выберите',
                                        'disabled' => true,
                                    ],
                                ]) ?>
                                        
                         <?= $form->field($model, 'carrier_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
                                    'data' => $clientData,
                                    'options' => [
                                        'placeholder' => 'Выберите',
                                        'disabled' => true,
                                    ],
                                ]) ?>
                                        
                         <?= $form->field($model, 'driver_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
                                    'data' => ArrayHelper::map(\app\models\Driver::find()->all(), 'id', 'data'),
                                    'options' => [
                                        'placeholder' => 'Выберите',
                                        'disabled' => true,
                                    ],
                            'pluginEvents' => [
                              'change' => 'function(){ $.get("/driver/view-ajax?id="+$(this).val(), function(response){ $("#flight-auto").val(response.data_avto); $("#flight-auto").trigger("change"); }); }'
                            ],
                                ]) ?>
                <div class="col-md-9"></div>
                <?= $form->field($model, 'auto', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
                            'data' => ArrayHelper::map(\app\models\Driver::find()->all(), 'data_avto', 'data_avto'),
                            'options' => [
                                'placeholder' => 'Выберите',
                                'disabled' => true,
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>




        <div class="col-md-9">
            <div class="row">
                 <?= $form->field($model, 'rout', ['cols' => 4, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>
                        
                 <?= $form->field($model, 'date', ['cols' => 4, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>

                 <?= $form->field($model, 'order', ['cols' => 4, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Загрузка и разгрузка</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?= $form->field($model, 'address1', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>
                                
                      <?= $form->field($model, 'shipping_date', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>
                                
                      <?= $form->field($model, 'shipping_date_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'disabled' => true])->label(false)  ?>

                                <?= $form->field($model, 'address_out4', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                                <?= $form->field($model, 'date_out4', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>

                      <?= $form->field($model, 'date_out4_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'disabled' => true])->label(false)  ?>

                                <?= $form->field($model, 'address_out2', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                                <?= $form->field($model, 'date_out2', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                      <?= $form->field($model, 'date_out2_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'disabled' => true])->label(false)  ?>

                                <?= $form->field($model, 'address_out5', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                                <?= $form->field($model, 'date_out5', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>

                      <?= $form->field($model, 'date_out5_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'disabled' => true])->label(false)  ?>

                                <?= $form->field($model, 'address_out3', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>
                    
                                <?= $form->field($model, 'date_out3', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>

                      <?= $form->field($model, 'date_out3_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'disabled' => true])->label(false)  ?>

                                <?= $form->field($model, 'address', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                                <?= $form->field($model, 'date_out6', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>

                      <?= $form->field($model, 'date_out6_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'disabled' => true])->label(false)  ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Контакты</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?= $form->field($model, 'telephone1', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                                <?= $form->field($model, 'telephone', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                                <?= $form->field($model, 'contact_out2', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                                <?= $form->field($model, 'contact_out', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                                <?= $form->field($model, 'contact', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>
                                
                                <?= $form->field($model, 'contact_out3', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
      
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Данные о перевозке</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                     <?= $form->field($model, 'view_auto', ['cols' => 12, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::view_autoLabels(), ['prompt' => 'Выберите вариант', 'disabled' => true]) ?>
                  
                     <?= $form->field($model, 'type', ['cols' => 12, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::typeLabels(), ['prompt' => 'Выберите вариант', 'disabled' => true]) ?>

                     <?= $form->field($model, 'name', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                     <?= $form->field($model, 'volume', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                     <?= $form->field($model, 'cargo_weight', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>


                     <?= $form->field($model, 'name2', ['cols' => 12, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
                        'data' => in_array($model->name2, array_keys(app\models\Flight::name2Labels())) ? app\models\Flight::name2Labels() : ArrayHelper::merge(app\models\Flight::name2Labels(), [$model->name2 => $model->name2]),
                        'options' => [
                            'placeholder' => 'Выберите',
                            'disabled' => true,
                            // 'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                       'allowClear' => true,
                        ],
                     ]) ?>

                     <?= $form->field($model, 'dop_informaciya_o_gruze', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>
                    </div>
                </div>
            </div>
        </div>

                
                    
                    

                    



                    
                    
                    




                    

                    
             

                    










      
                    

                    


             <?= $form->field($model, 'we', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

         <?= $form->field($model, 'pay_us', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::pay_usLabels(), ['prompt' => 'Выберите вариант', 'disabled' => true]) ?>
         <?= $form->field($model, 'payment1', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::payment1Labels(), ['prompt' => 'Выберите вариант', 'disabled' => true]) ?>


             <?= $form->field($model, 'col2', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>


             <?= $form->field($model, 'payment_out', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>


                    
         <?= $form->field($model, 'otherwise2', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::otherwise2Labels(), ['prompt' => 'Выберите вариант', 'disabled' => true]) ?>
      
                    
         <?= $form->field($model, 'otherwise3', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::otherwise3Labels(), ['prompt' => 'Выберите вариант', 'disabled' => true]) ?>

             <?= $form->field($model, 'col1', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <!--        <div class="panel-heading-btn">-->
                    <!--        </div>-->
                    <h4 class="panel-title">Счет</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                         <?= $form->field($model, 'fio', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList([
                            'Стандарт' => 'Стандарт',
                            'Срыв погрузки' => 'Срыв погрузки',
                            'Предоплата' => 'Предоплата',
                            'Ваш текст' => 'Ваш текст',
                         ], ['prompt' => 'Выберите', 'disabled' => true])  ?>


                         <?= $form->field($model, 'date_cr', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>

                         <?= $form->field($model, 'number', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>


                         <?= $form->field($model, 'upd', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                         <?= $form->field($model, 'otherwise4', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                         <?= $form->field($model, 'otherwise', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>

                         <?= $form->field($model, 'recoil', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>


                         <?= $form->field($model, 'your_text', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])  ?>
                         


                                
                                
                         <?= $form->field($model, 'date2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>
                                
                         <?= $form->field($model, 'date3', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['disabled' => true])  ?>
                         <?= $form->field($model, 'name3', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['disabled' => true])->label('Информацию по рейсу')  ?>
                    </div>
                </div>
            </div>
        </div>



                    
             <?= $form->field($model, 'is_register', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox(['disabled' => true])  ?>
             <?php if(\Yii::$app->user->identity->can('flight_is_order')): ?>
             <?= $form->field($model, 'is_order', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox(['disabled' => true])  ?>
             <?php endif; ?>
             <?php if(\Yii::$app->user->identity->can('flight_is_signature')): ?>
             <?= $form->field($model, 'is_signature', ['cols' => 8, 'colsOptionsStr' => " "])->checkbox(['disabled' => true])  ?>
             <?php endif; ?>

             <div class="row">
               <div class="col-md-12">
                 
               </div>
             </div>

                    
                    
                    <div class="row">
                        <div class="col-md-6" <?= \Yii::$app->user->identity->can('flight_upload_file') ? '' : 'style="display: none;"' ?>>
                            <div class="row">
                <div class="col-md-7" style="height: 200px; overflow: auto;">
                    <label>Заказчик</label>
                    <table role="presentation" class="table table-striped">
                        <tbody class="files">
                        <?php if ($model->file):?>
                        <?php  $counter = 0; foreach (json_decode($model->file, true) as $value):?>
                          <tr class="template-upload fade in">
                                <td>
                                    <span class="preview">
                                      <img src="<?php if(file_exists(substr($value['url'], 1)) !== false){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
                                    </span>
                                </td>
                                <td>
                                    <p class="name"></p><?=$value['name']?></p>
                                    <p class="size"><?=$value['size']?></p>
                                </td>
                                <td>
                                <a class="btn btn-primary image-popup-vertical-fit" href="<?= $value['url'] ?>">
                                    <span>Просмотр</span>
                                </a>
                                <?php

                                    $extension = explode('.', $value['url']);
                                    if(isset($extension[1])){
                                        $extension = $extension[1];
                                    }

                                ?>
                                <a class="btn btn-success" href="<?= \yii\helpers\Url::toRoute(['flight/download-resource', 'path' => $value['url']]) ?>" download="<?= $value['name'] ?>">
                                    <span>Скачать</span>
                                </a>
                                    
                                </td>
                            </tr>
                            <?php $counter++; ?>
                        <?php endforeach;?>
<?php

$script = <<< JS
    $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
            verticalFit: true
        }
        
    });
JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>    
                        <?php endif;?>
                      </tbody>
                  </table>
              </div>
                            </div>                          
                        </div>
                        <div class="col-md-6" <?= \Yii::$app->user->identity->can('flight_driver_upload_file') ? '' : 'style="display: none;"' ?>>
                            <div class="row">

                <div class="col-md-12" style="height: 200px; overflow: auto;">
                    <label>Перевозчик</label>
                    <table role="presentation" class="table table-striped">
                        <tbody class="files-provider">
                        <?php if ($model->file_provider):?>
                        <?php  $counter = 0; foreach (json_decode($model->file_provider, true) as $value):?>
                          <tr class="template-upload fade in">
                                <td>
                                    <span class="preview">
                                      <img src="<?php if(file_exists(substr($value['url'], 1)) !== false){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
                                    </span>
                                </td>
                                <td>
                                    <p class="name"></p><?=$value['name']?></p>
                                    <p class="size"><?=$value['size']?></p>
                                </td>
                                <td>
                                <a class="btn btn-primary image-popup-vertical-fit" href="<?= $value['url'] ?>">
                                    <span>Просмотр</span>
                                </a>
                                <?php

                                    $extension = explode('.', $value['url']);
                                    if(isset($extension[1])){
                                        $extension = $extension[1];
                                    }

                                ?>
                                <a class="btn btn-success" <?= \yii\helpers\Url::toRoute(['flight/download-resource', 'path' => $value['url']]) ?> download="<?= $value['name'] ?>">
                                    <span>Скачать</span>
                                </a>
                                    
                                </td>
                            </tr>
                            <?php $counter++; ?>
                        <?php endforeach;?>
<?php

$script = <<< JS
    $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
            verticalFit: true
        }
        
    });
JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>    
                        <?php endif;?>
                      </tbody>
                  </table>
              </div>
                        </div>
                    </div>
            

    </div>

    <?php ActiveForm::end(); ?>
    </div>
    </div>
    
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<!-- <script src="/libs/jquery.maskedinput.min.js"></script> -->

<!-- <script>$("#client-phone").mask("+7 (999) 999-9999");</script>-->


<?php

$script = <<< JS

$("#flight-fio").change(function(){
    var value = $(this).val();


    if(value == 'Стандарт'){

    }
    if(value == 'Срыв погрузки'){

    }
    if(value == 'Предоплата'){

    }
    if(value == 'Ваш текст'){
        
    }
});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);


                // 'Стандарт' => 'Стандарт',
                // 'Срыв погрузки' => 'Срыв погрузки',
                // 'Предоплата' => 'Предоплата',
                // 'Ваш текст' => 'Ваш текст',

?>