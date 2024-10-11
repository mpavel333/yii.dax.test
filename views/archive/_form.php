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


// $managerReadonly = Yii::$app->user->identity->isSuperAdmin() == false && ($model->is_register || $model->is_signature);
// $managerReadonly = stripos(Yii::$app->user->identity->getRoleName(), "Заказчик") == -1 && ($model->is_register || $model->is_signature);
$managerReadonly = \Yii::$app->user->identity->isManager() && $model->is_signature;

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

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
                                               

        <?php
        $requisiteData = [];
        foreach (\app\models\Requisite::find()->all() as $requisite) {
            $requisiteData[$requisite->id] = $requisite->name." ({$requisite->inn}) ".$requisite->bank_name;
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
				                        // 'disabled' => $managerReadonly,
				                    ],
				                ]) ?>
				                    
				            <?php
				            $clientData = [];
				            $clientQuery = \app\models\Client::find()->where(['like', 'users', '"'.Yii::$app->user->getId().'"']);

				            if($model->zakazchik_id){
				            	$clientQuery->orWhere(['id' => $model->zakazchik_id]);
				            }

				            if($model->carrier_id){
				            	$clientQuery->orWhere(['id' => $model->carrier_id]);
				            }


				            foreach ($clientQuery->all() as $client) {
				                $clientData[$client->id] = $client->name." ({$client->inn})";
				            }



				            ?>         
				         <?= $form->field($model, 'zakazchik_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
				                    'data' => $clientData,
				                    'options' => [
				                        'placeholder' => 'Выберите',
				                        'disabled' => $managerReadonly,
				                    ],
				                ]) ?>
				                        
				         <?= $form->field($model, 'carrier_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
				                    'data' => $clientData,
				                    'options' => [
				                        'placeholder' => 'Выберите',
				                        'disabled' => $managerReadonly,
				                    ],
				                ]) ?>
				                        

				           <?php

				           	$driverData = [];

				           	foreach (\app\models\Driver::find()->all() as $driver) {
				           		$driverData[$driver->id] = "{$driver->data}"; 
				           	}

				           ?>

				         <?= $form->field($model, 'driver_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
				                    'data' => $driverData,
				                    'options' => [
				                        'placeholder' => 'Выберите',
				                        'disabled' => $managerReadonly,
				                    ],
                            'pluginEvents' => [
                              'change' => 'function(){ $.get("/driver/view-ajax?id="+$(this).val(), function(response){ $("#flight-auto").val(response.id); $("#flight-auto").trigger("change"); }); }'
                            ],
				                ]) ?>
                <?php
                // echo  $form->field($model, 'bank_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
                //             'data' => ArrayHelper::map(\app\models\Bank::find()->all(), 'id', 'name'),
                //             'options' => [
                //                 'placeholder' => 'Выберите',
                //                 'disabled' => $managerReadonly,
                //             ],
                //             'pluginOptions' => [
                //             	'allowClear' => true,
                //             ],
                //         ])
                        ?>

                <div class="col-md-9"></div>

				           <?php

				           	$driverData = [];

				           	foreach (\app\models\Driver::find()->all() as $driver) {
				           		$driverData[$driver->id] = "{$driver->data_avto} {$driver->car_number} {$driver->car_truck_number}"; 
				           	}

				           ?>
                <?= $form->field($model, 'auto', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
                            'data' => $driverData,
                            'options' => [
                                'placeholder' => 'Выберите',
                                'disabled' => $managerReadonly,
                            ],
                        ]) ?>
			    	</div>
			    </div>
			</div>
        </div>




        <div class="col-md-9">
        	<div class="row">
	             <?= $form->field($model, 'rout', ['cols' => 2, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
	             
		         <?= $form->field($model, 'distance', ['cols' => 2, 'colsOptionsStr' => " "])->textInput(['readonly' => true,])->label('Расстояние (км)')  ?>
	                    
	             <?= $form->field($model, 'date', ['cols' => 4, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>

	             <div class="col-md-4">
	             	<div class="row">
	             		<?php if(\Yii::$app->user->identity->can('flight_order')): ?>
	             			<?= $form->field($model, 'order', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()->label('Заявка')  ?>
	             		<?php endif; ?>
	             		<?php if(\Yii::$app->user->identity->can('flight_driver_order')): ?>
	             			<?= $form->field($model, 'driver_order', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()  ?>
	             		<?php endif; ?>
	             	</div>
	             </div>
        	</div>
        	<div class="row">
        		<div class="col-md-8">
					<div class="panel panel-default">
					    <div class="panel-heading">
					        <h4 class="panel-title">Загрузка и разгрузка</h4>
					    </div>
					    <div class="panel-body">
							<div class="row">
             					<?= $form->field($model, 'address1', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
								
                      <?= $form->field($model, 'shipping_date', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>
             					
             			<?php if(\Yii::$app->user->identity->can('flight_dates')): ?>
	                      <?= $form->field($model, 'shipping_date_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'readonly' => false])->label(false)  ?>

             			<?php endif; ?>

					            <?= $form->field($model, 'address_out4', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

             					<?= $form->field($model, 'date_out4', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>

             					<?php if(\Yii::$app->user->identity->can('flight_dates')): ?>
                      <?= $form->field($model, 'date_out4_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'readonly' => false])->label(false)  ?>
                      	<?php endif; ?>

             					<?= $form->field($model, 'address_out2', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

             					<?= $form->field($model, 'date_out2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>

             					<?php if(\Yii::$app->user->identity->can('flight_dates')): ?>
                      <?= $form->field($model, 'date_out2_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'readonly' => false])->label(false)  ?>
                  <?php endif; ?>

					            <?= $form->field($model, 'address_out5', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

					            <?= $form->field($model, 'date_out5', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>

						<?php if(\Yii::$app->user->identity->can('flight_dates')): ?>
                      <?= $form->field($model, 'date_out5_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'readonly' => false])->label(false)  ?>
                  <?php endif; ?>

             					<?= $form->field($model, 'address_out3', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
                    
             					<?= $form->field($model, 'date_out3', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>

						<?php if(\Yii::$app->user->identity->can('flight_dates')): ?>
                      <?= $form->field($model, 'date_out3_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'readonly' => false])->label(false)  ?>
                      	<?php endif; ?>

					            <?= $form->field($model, 'address', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

					            <?= $form->field($model, 'date_out6', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>

					            <?php if(\Yii::$app->user->identity->can('flight_dates')): ?>

                      <?= $form->field($model, 'date_out6_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'readonly' => false])->label(false)  ?>
							
								<?php endif; ?>
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
            					<?= $form->field($model, 'telephone1', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            					<?= $form->field($model, 'telephone', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            					<?= $form->field($model, 'contact_out2', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            					<?= $form->field($model, 'contact_out', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

             					<?= $form->field($model, 'contact', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
				            	
				            	<?= $form->field($model, 'contact_out3', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
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
			         <?= $form->field($model, 'view_auto', ['cols' => 12, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::view_autoLabels(), ['prompt' => 'Выберите вариант', 'disabled' => false]) ?>
			      
			         <?= $form->field($model, 'type', ['cols' => 12, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::typeLabels(), ['prompt' => 'Выберите вариант', 'disabled' => false]) ?>

		             <?= $form->field($model, 'name', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

		             <?= $form->field($model, 'volume', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            		 <?= $form->field($model, 'cargo_weight', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>


		             <?= $form->field($model, 'name2', ['cols' => 12, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
		                'data' => in_array($model->name2, array_keys(app\models\Flight::name2Labels())) ? app\models\Flight::name2Labels() : ArrayHelper::merge(app\models\Flight::name2Labels(), [$model->name2 => $model->name2]),
		                'options' => [
		                    'placeholder' => 'Выберите',
		                    'disabled' => false,
		                    // 'multiple' => true,
		                ],
		                'pluginOptions' => [
		                    'tags' => true,
                       'allowClear' => true,
		                ],
		             ]) ?>

		             <?= $form->field($model, 'dop_informaciya_o_gruze', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
			    	</div>
			    </div>
			</div>
        </div>

                
                    
                    

                    



                    
                    
                    




                    

                    
             

                    










      
                    

                    


             <?= $form->field($model, 'we', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonly])  ?>

         <?= $form->field($model, 'pay_us', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::pay_usLabels(), ['prompt' => 'Выберите вариант', 'disabled' => $managerReadonly]) ?>
         <?= $form->field($model, 'payment1', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::payment1Labels(), ['prompt' => 'Выберите вариант', 'disabled' => $managerReadonly]) ?>


             <?= $form->field($model, 'col2', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonly])  ?>


             <?= $form->field($model, 'payment_out', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonly])  ?>


                    
         <?= $form->field($model, 'otherwise2', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::otherwise2Labels(), ['prompt' => 'Выберите вариант', 'disabled' => $managerReadonly]) ?>
      
                    
         <?= $form->field($model, 'otherwise3', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::otherwise3Labels(), ['prompt' => 'Выберите вариант', 'disabled' => $managerReadonly]) ?>

             <?= $form->field($model, 'col1', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonly])  ?>

             <?= $form->field($model, 'act', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonly])  ?>

             <?= $form->field($model, 'act_date', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => $managerReadonly])  ?>

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
			             ], ['prompt' => 'Выберите', 'disabled' => $managerReadonly])  ?>


			             <?= $form->field($model, 'date_cr', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => $managerReadonly])  ?>

			             <?= $form->field($model, 'number', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonly])  ?>


			             <?= $form->field($model, 'upd', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonly])  ?>

			             <?= $form->field($model, 'otherwise4', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

			             <?= $form->field($model, 'otherwise', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

			             <?= $form->field($model, 'recoil', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>


			             <?= $form->field($model, 'your_text', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
			             


			                    
			                    
			             <div class="col-md-3">
			             	<div class="row">
				             	<?= $form->field($model, 'date2', ['cols' => 6, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>
			             		<?= $form->field($model, 'track_number', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
			             	</div>
			             	<div class="row">
			             		<?= $form->field($model, 'letter_info', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
			             	</div>
			             </div>

			             <div class="col-md-3">
			             	<div class="row">
				            	<?= $form->field($model, 'date3', ['cols' => 6, 'colsOptionsStr' => " "])->input('date', ['readonly' => false])  ?>
				            	<?= $form->field($model, 'track_number_driver', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
			             	</div>
			             	<div class="row">
			             		<?= $form->field($model, 'letter_info_driver', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
			             	</div>
			             </div>
			                    
			             <?= $form->field($model, 'name3', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => false])->label('Информацию по рейсу')  ?>

			             <?= $form->field($model, 'info_from_client', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => false]) ?>
			    	</div>
			    </div>
			</div>
        </div>



                    
              <?php if(\Yii::$app->user->identity->isSuperAdmin()): ?>
             	<?= $form->field($model, 'is_register', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox()  ?>
              <?php else: ?>
	             <?= $form->field($model, 'is_register', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox(['disabled' => boolval($model->is_signature)])  ?>
              <?php endif; ?>
             <?php if(\Yii::$app->user->identity->can('flight_is_order')): ?>
             <?= $form->field($model, 'is_order', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox()  ?>
             <?php endif; ?>
             <?php if(\Yii::$app->user->identity->can('flight_is_signature')): ?>
             <?= $form->field($model, 'is_signature', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox()  ?>
             <?php endif; ?>
             <?php if(\Yii::$app->user->identity->can('flight_driver_signature')): ?>
			<?= $form->field($model, 'is_driver_signature', ['cols' => 4, 'colsOptionsStr' => " "])->checkbox()  ?>
			 <?php endif; ?>

			 	<?= $form->field($model, 'is_register_letter', ['cols' => 4, 'colsOptionsStr' => " "])->checkbox() ?>
			 	<?= $form->field($model, 'is_register_letter_driver', ['cols' => 4, 'colsOptionsStr' => " "])->checkbox()  ?>


             <div class="row">
               <div class="col-md-12">
                 
               </div>
             </div>

                    
                    
                    <div class="row">
                    	<div class="col-md-6" <?= \Yii::$app->user->identity->can('flight_upload_file') ? '' : 'style="display: none;"' ?>>
							<div class="row">
                <div class="col-md-5" style="height: 200px; overflow: auto;">
                      <label>Заказчик</label>
            <?= \kato\DropZone::widget([
    'id'        => 'dzImage_file',
    'uploadUrl' => \yii\helpers\Url::toRoute([ '/flight/upload-file']),
    'dropzoneContainer' => 'dz-container-images',
    'previewsContainer' => 'preview-images', // <-- уникальные previewsContainer,
    'options' => [
    'dictDefaultMessage' => 'Перетащите файлы сюда',
    ],
    'clientEvents' => [
      'complete' => "function(file){
        var path = JSON.parse(file.xhr.response);
        if($('[name=\'file_file_path\']').val()){
          var value = JSON.parse($('[name=\'file_file_path\']').val());
        } else {
          var value = [];
        }        console.log(value, 'current value');
        value.push(path);
        console.log(value, 'new value');        $('[name=\'file_file_path\']').val(JSON.stringify(value));
        var arr = [ 'jpeg', 'jpg', 'gif', 'png' ];
        var strE = path.url;        var ext = strE.substring(strE.lastIndexOf('.')+1);
        if(arr.includes(ext)){
          strE = path.url;
        } else {
          strE = 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';
        }
        var html = '<tr class=\"template-upload fade in\"><td><span class=\"preview\"><img src=\"'+strE+'\" style=\"width: 50px;\"></span></td>';
        html += '<td><p class=\"name\">'+path.name+'</p><p class=\"size\">'+path.size+'</p></td>';
        html += '<td><button data-delete=\"'+path.url+'\" class=\"btn btn-danger cancel\"><i class=\"glyphicon glyphicon-ban-circle\"></i><span>Удалить</span></button></td></tr>';
        $('.files').append(html);
        $('.files [data-delete]').unbind('click');
        $('.files [data-delete]').click(deleteEventHandler);
      }",
     ],
]);?>
<div style="display: none;">
  <input type="hidden" name="file_file_path">
</div>
<?php
$script = <<< JS

var deleteEventHandler = function(e){
  e.preventDefault();

  var path = $(this).data('delete');

  var self = $(this).parent().parent();

  if($('[name=\'file_file_path\']').val()){
    var data = JSON.parse($('[name=\'file_file_path\']').val());
  } else {
    var data = [];
  }

  var newData = [];

  console.log(data, 'current data');

  $.each(data, function(){
      if(this.url != path){
        newData.push(this);
      }
  });

  console.log(newData, 'new data');

  $('[name=\'file_file_path\']').val(JSON.stringify(newData));




  $.get("/flight/image-delete?id={$model->id}&name="+path, function(){
    $(self).remove();
  });
};

$('.files [data-delete]').click(deleteEventHandler);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style="height: 200px; overflow: auto;">
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
                                    <?= \yii\helpers\Html::dropDownList('fileType'.$counter, (isset($value['type']) ? $value['type'] : null), [
                                        'Заявка' => 'Заявка',
                                        'Договор' => 'Договор',
                                        'Счёт' => 'Счёт',
                                        'УПД' => 'УПД',
                                        'АКТ' => 'АКТ',
                                        'Закрывающие' => 'Закрывающие',
                                    ], ['class' => 'form-control', 'prompt' => 'Выберите', 'onchange' => '$.get("/flight/update-file-attr?id='.$model->id.'&i='.$counter.'&value="+$(this).val());']) ?>
                                </td>
                                <td>
                                <button data-delete="<?=$value['url']?>" class="btn btn-danger cancel" onclick="deleteEventHandler">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Удалить</span>
                                </button>
                                <a class="btn btn-primary" target="_blank" href="<?= \yii\helpers\ArrayHelper::getValue($value, 'preview_url') ?>">
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
                <div class="col-md-5" style="height: 200px; overflow: auto;">
                      <label>Перевозчик</label>
            <?= \kato\DropZone::widget([
    'id'        => 'dzImage_file_provider',
    'uploadUrl' => \yii\helpers\Url::toRoute([ '/flight/upload-file']),
    'dropzoneContainer' => 'dzz-container-images',
    'previewsContainer' => 'previeww-images', // <-- уникальные previewsContainer
    'options' => [
    'dictDefaultMessage' => 'Перетащите файлы сюда',
    ],
    'clientEvents' => [
      'complete' => "function(file){
        var path = JSON.parse(file.xhr.response);
        if($('[name=\'file_provider_file_path\']').val()){
          var value = JSON.parse($('[name=\'file_provider_file_path\']').val());
        } else {
          var value = [];
        }        console.log(value, 'current value');
        value.push(path);
        console.log(value, 'new value');        $('[name=\'file_provider_file_path\']').val(JSON.stringify(value));
        var arr = [ 'jpeg', 'jpg', 'gif', 'png' ];
        var strE = path.url;        var ext = strE.substring(strE.lastIndexOf('.')+1);
        if(arr.includes(ext)){
          strE = path.url;
        } else {
          strE = 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';
        }
        var html = '<tr class=\"template-upload fade in\"><td><span class=\"preview\"><img src=\"'+strE+'\" style=\"width: 50px;\"></span></td>';
        html += '<td><p class=\"name\">'+path.name+'</p><p class=\"size\">'+path.size+'</p></td>';
        html += '<td><button data-delete=\"'+path.url+'\" class=\"btn btn-danger cancel\"><i class=\"glyphicon glyphicon-ban-circle\"></i><span>Удалить</span></button></td></tr>';
        $('.files-provider').append(html);
        $('.files-provider [data-delete]').unbind('click');
        $('.files-provider [data-delete]').click(deleteEventHandlerProvider);
      }",
     ],
]);?>
<div style="display: none;">
  <input type="hidden" name="file_provider_file_path">
</div>
<?php
$script = <<< JS

var deleteEventHandlerProvider = function(e){
  e.preventDefault();

  var path = $(this).data('delete');

  var self = $(this).parent().parent();

  if($('[name=\'file_provider_file_path\']').val()){
    var data = JSON.parse($('[name=\'file_provider_file_path\']').val());
  } else {
    var data = [];
  }

  var newData = [];

  console.log(data, 'current data');

  $.each(data, function(){
      if(this.url != path){
        newData.push(this);
      }
  });

  console.log(newData, 'new data');

  $('[name=\'file_provider_file_path\']').val(JSON.stringify(newData));




  $.get("/flight/image-delete-provider?id={$model->id}&name="+path, function(){
    $(self).remove();
  });
};

$('.files-provider [data-delete]').click(deleteEventHandlerProvider);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style="height: 200px; overflow: auto;">
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
                                    <?= \yii\helpers\Html::dropDownList('fileType'.$counter, (isset($value['type']) ? $value['type'] : null), [
                                        'Заявка' => 'Заявка',
                                        'Договор' => 'Договор',
                                        'Счёт' => 'Счёт',
                                        'УПД' => 'УПД',
                                        'АКТ' => 'АКТ',
                                        'Закрывающие' => 'Закрывающие',
                                    ], ['class' => 'form-control', 'prompt' => 'Выберите', 'onchange' => '$.get("/flight/update-file-provider-attr?id='.$model->id.'&i='.$counter.'&value="+$(this).val());']) ?>
                                </td>
                                <td>
                                <button data-delete="<?=$value['url']?>" class="btn btn-danger cancel" onclick="deleteEventHandlerProvider">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Удалить</span>
                                </button>
                                <a class="btn btn-primary" target="_blank" href="<?= \yii\helpers\ArrayHelper::getValue($value, 'preview_url') ?>">
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
            

    </div>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<!-- <script src="/libs/jquery.maskedinput.min.js"></script> -->

<!-- <script>$("#client-phone").mask("+7 (999) 999-9999");</script>-->

<div style="display: none;">
	<div id="map" style="width: 500px; height: 500px;">
	
	</div>
	<div id="viewContainer"></div>
</div>

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


$script = <<< JS

ymaps.modules.define('MultiRouteCustomView', [
    'util.defineClass'
], function (provide, defineClass) {
    // Класс простого текстового отображения модели мультимаршрута.
    function CustomView (multiRouteModel) {
        this.multiRouteModel = multiRouteModel;
        // Объявляем начальное состояние.
        this.state = "init";
        this.stateChangeEvent = null;
        // Элемент, в который будет выводиться текст.
        this.outputElement = $('<div></div>').appendTo('#viewContainer');

        this.rebuildOutput();

        // Подписываемся на события модели, чтобы
        // обновлять текстовое описание мультимаршрута.
        multiRouteModel.events
            .add(["requestsuccess", "requestfail", "requestsend"], this.onModelStateChange, this);
    }

    // Таблица соответствия идентификатора состояния имени его обработчика.
    CustomView.stateProcessors = {
        init: "processInit",
        requestsend: "processRequestSend",
        requestsuccess: "processSuccessRequest",
        requestfail: "processFailRequest"
    };

    // Таблица соответствия типа маршрута имени его обработчика.
    CustomView.routeProcessors = {
        "driving": "processDrivingRoute",
        "masstransit": "processMasstransitRoute",
        "pedestrian": "processPedestrianRoute"
    };

    defineClass(CustomView, {
        // Обработчик событий модели.
        onModelStateChange: function (e) {
            // Запоминаем состояние модели и перестраиваем текстовое описание.
            this.state = e.get("type");
            this.stateChangeEvent = e;
            this.rebuildOutput();
        },

        rebuildOutput: function () {
            // Берем из таблицы обработчик для текущего состояния и исполняем его.
            var processorName = CustomView.stateProcessors[this.state];
            this.outputElement.html(
                this[processorName](this.multiRouteModel, this.stateChangeEvent)
            );
        },

        processInit: function () {
            return "Инициализация ...";
        },

        processRequestSend: function () {
            return "Запрос данных ...";
        },

        processSuccessRequest: function (multiRouteModel, e) {
            var routes = multiRouteModel.getRoutes(),
                result = ["Данные успешно получены."];
            if (routes.length) {
                result.push("Всего маршрутов: " + routes.length + ".");
                // for (var i = 0, l = routes.length; i < l; i++) {
                    result.push(this.processRoute(0, routes[0]));
                // }
            } else {
                result.push("Нет маршрутов.");
            }
            return result.join("<br/>");
        },

        processFailRequest: function (multiRouteModel, e) {
            return e.get("error").message;
        },

        processRoute: function (index, route) {
            // Берем из таблицы обработчик для данного типа маршрута и применяем его.
            var processorName = CustomView.routeProcessors[route.properties.get("type")];
            return (index + 1) + ". " + this[processorName](route);
        },

        processDrivingRoute: function (route) {
            var result = ["Автомобильный маршрут."];
            result.push(this.createCommonRouteOutput(route));
            return result.join("<br/>");
        },

        processMasstransitRoute: function (route) {
            var result = ["Маршрут на общественном транспорте."];
            result.push(this.createCommonRouteOutput(route));
            result.push("Описание маршута: <ul>" + this.createMasstransitRouteOutput(route) + "</ul>");
            return result.join("<br/>");
        },

        processPedestrianRoute: function (route) {
            var result = ["Пешеходный маршрут."];
            result.push(this.createCommonRouteOutput(route));
            return result.join("<br/>");
        },

        // Метод, формирующий общую часть описания для всех типов маршрутов.
        createCommonRouteOutput: function (route) {
        	var distanceValue = route.properties.get("distance").text;

        	distanceValue = distanceValue.replace(' км', '');
        	distanceValue = distanceValue.replace(',', '.');
        	distanceValue = distanceValue.replace(/\s/, '');

        	$('#flight-distance').val(distanceValue);
            return "Протяженность маршрута: " + route.properties.get("distance").text + "<br/>" +
                "Время в пути: " + route.properties.get("duration").text;
        },

        // Метод, строящий список текстовых описаний для
        // всех сегментов маршрута на общественном транспорте.
        createMasstransitRouteOutput: function (route) {
            var result = [];
            for (var i = 0, l = route.getPaths().length; i < l; i++) {
                var path = route.getPaths()[i];
                for (var j = 0, k = path.getSegments().length; j < k; j++) {
                    result.push("<li>" + path.getSegments()[j].properties.get("text") + "</li>");
                }
            }
            return result.join("");
        },

        destroy: function () {
            this.outputElement.remove();
            this.multiRouteModel.events
                .remove(["requestsuccess", "requestfail", "requestsend"], this.onModelStateChange, this);
        }
    });

    provide(CustomView);
});


function init () {
    // Создаем модель мультимаршрута.
    var multiRouteModel = new ymaps.multiRouter.MultiRouteModel(window.routes, {
        });



    ymaps.modules.require([
        'MultiRouteCustomView'
    ], function (MultiRouteCustomView) {
        // Создаем экземпляр текстового отображения модели мультимаршрута.
        // см. файл custom_view.js
        new MultiRouteCustomView(multiRouteModel);
    });

    // Создаем карту с добавленной на нее кнопкой.
    var myMap = new ymaps.Map('map', {
            center: [55.750625, 37.626],
            zoom: 7,
            controls: []
        }, {
            buttonMaxWidth: 300
        }),

        // Создаем на основе существующей модели мультимаршрут.
        multiRoute = new ymaps.multiRouter.MultiRoute(multiRouteModel, {
        });

    // Добавляем мультимаршрут на карту.
    myMap.geoObjects.add(multiRoute);
}

// ymaps.ready(init);

$("#flight-rout").change(function(){
	var value = $(this).val();

	value = value.split(/\s[-,—]\s/);

	console.log(value, 'cities');

	if(value[0] && value[1]){
		// window.mapFrom = value[0].trim();
		// window.mapTo = value[1].trim();

		window.routes = value;


		$("#map").html(null);
		$("#viewContainer").html(null);

		ymaps.ready(init);
	}
});


JS;

$this->registerJs($script, \yii\web\View::POS_READY);


?>
