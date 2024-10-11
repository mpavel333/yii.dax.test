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


$dateCr = new \DateTime($model->date_cr);
$dateCr->modify('+2 days');

// $managerReadonly = \Yii::$app->user->identity->isManager() && $model->is_signature && $model->date_cr <= date('Y-m-d');
$managerReadonly = (\Yii::$app->user->identity->isManager() || \Yii::$app->user->identity->can('flight_btn_update')) && $model->is_signature && $dateCr->format('Y-m-d') <= date('Y-m-d');

// $managerReadonlyDateTime = $managerReadonly && $model->date_cr <= date('Y-m-d');
$managerReadonlyDateTime = $managerReadonly && $dateCr->format('Y-m-d') <= date('Y-m-d');
$managerReadonlyDateTimeTop = $managerReadonly && $dateCr->format('Y-m-d') <= date('Y-m-d') && $model->is_signature && $model->is_driver_signature;





if($model->isNewRecord){
	$model->fio = 'Стандарт';
	$model->type_weight = \app\models\Flight::WEIGHT_TYPE_TONS;

	$flightCount = \app\models\Flight::find()->orderBy('id desc')->one();
	if($flightCount){
		$flightCount = $flightCount->index;
	} else {
		$flightCount = 1;
	}
	if(\Yii::$app->user->identity->role){
		$userPks = \yii\helpers\ArrayHelper::getColumn(\app\models\User::find()->where(['role' => \Yii::$app->user->identity->role])->all(), 'id');
		$flightCount = (string) \app\models\Flight::find()->where(['created_by' => $userPks])->andWhere(['>', 'date', date('Y').'-01-01'])->count();
		// $flightCount = \app\models\Flight::find()->orderBy('id desc')->one();
		// if($flightCount){
		// 	$flightCount = $flightCount->index;
		// } else {
		// 	$flightCount = 1;
		// }
		if(mb_strlen($flightCount) == 1){
			$flightCount = '00'.$flightCount;
		} elseif(mb_strlen($flightCount) == 2) {
			$flightCount = '0'.$flightCount;
		}
		$model->order = \Yii::$app->user->identity->role.$flightCount + 1;
		// $model->number = \Yii::$app->user->identity->role.$flightCount + 1;
		// $model->upd = \Yii::$app->user->identity->role.$flightCount + 1;
	}

	if(\Yii::$app->request->isGet){
		$model->date = date('Y-m-d');
	}
	// $model->index = \app\models\Flight::find()->count() + 1;
	$model->index = $flightCount + 1;
}



$group = isset($_GET['group']) ? true : false;


if($model->flights_count == null){
	$model->flights_count = 1;
}


$cache = \Yii::$app->cache;

$clients = \app\models\Client::find()->where(['user_id'=>\Yii::$app->user->id])->select(['id', 'name', 'inn'])->all();

/*
$clients = $cache->get('flight_client_list');
if($clients == null){
	$clients = \app\models\Client::find()->select(['id', 'name', 'inn'])->all();
	$cache->set('flight_client_list', $clients);
}
*/
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

    <?php $form = ActiveForm::begin(['options'=>['data-id'=>$model->id]]); ?>

    <div class="row">
                                               

        <?php
        $requisiteData = [];
        foreach (\app\models\Requisite::find()->where(['is_hidden' => false])->all() as $requisite) {
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
				                        'disabled' => $managerReadonlyDateTimeTop,
				                    ],
				                ]) ?>
				                    
				            <?php
				            $clientData = [];
				            $clientQuery = $clients;
							
							/*
							$clientQuery = array_filter($clients, function($client){
								return mb_stripos($client->users, '"'.Yii::$app->user->getId().'"');
							});
					

				            if($model->zakazchik_id){
				            	// $clientQuery->orWhere(['id' => $model->zakazchik_id]);
								$clientQuery = array_filter($clients, function($client) use($model){
									return $client->id == $model->zakazchik_id;
								});
				            }

				            if($model->carrier_id){
				            	// $clientQuery->orWhere(['id' => $model->carrier_id]);
								$clientQuery = array_filter($clients, function($client) use($model){
									return $client->id == $model->carrier_id;
								});
				            }
							*/

				            foreach ($clientQuery as $client) {
				                $clientData[$client->id] = $client->name." ({$client->inn})";
				            }



				            ?>         
				         <?= $form->field($model, 'zakazchik_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
				                    'data' => $clientData,
				                    'options' => [
				                        'placeholder' => 'Выберите',
				                        'disabled' => $managerReadonlyDateTimeTop,
				                    ],
									'pluginEvents' => [
										'change' => 'function(){ 
                                                                                                
                                                                                                let select = this;
                                                                                                
												$.get("/client/view-ajax-id?id="+$(this).val(), function(response){ 
                                                                                                
                                                                                                        if (response.contract == 0 && response.contract_orig == 0){
                                                                                                            alert("У данного заказчика отсутствует Договор");
                                                                                                            select.selectedIndex = 0;
                                                                                                            return;
                                                                                                        }
													if (response.comment){
														if (confirm("Заменить поле \"Комментарий из таблицы Клиенты\" полем из таб. Заказчик-Комментарий?")) {
															$("#flight-client_comment").val(response.comment); 
														} else {
															return false;
														}
													}

													
												});}'
									  ],


				                ]) ?>
				                        
				         <?= $form->field($model, 'carrier_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
				                    'data' => $clientData,
				                    'options' => [
				                        'placeholder' => 'Выберите',
				                        'disabled' => $managerReadonlyDateTimeTop,
				                    ],
				                ]) ?>
				                        

				           <?php

				           	$driverData = [];
								   
                                                $drivers = \app\models\Driver::find()->select(['id', 'data', 'data_avto', 'car_number','car_truck_number'])->all();

                                                /*
							   $cache = \Yii::$app->cache;

							   $drivers = $cache->get('flight_drivers_auto_list');
							   if($drivers == null){
								   $drivers = \app\models\Driver::find()->select(['id', 'data', 'data_avto', 'car_number','car_truck_number'])->all();
								   $cache->set('flight_drivers_auto_list', $drivers);
							   }
                                                */

							foreach ($drivers as $driver) {
								$driverData[$driver->id] = $driver->data; 
							}


				           ?>

				         <?= $form->field($model, 'driver_id', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
				                    'data' => $driverData,
				                    'options' => [
				                        'placeholder' => 'Выберите',
				                        'disabled' => $managerReadonlyDateTimeTop,
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

                <?php
                	// $banks = \app\models\Bank::find()->all();
                	// $banksData = [];
                	// foreach($banks as $bank)
                	// {
                	// 	$banksData[$bank->id] = "{$bank->name} ({$bank->number})";
                	// }

                	$banks = [];

                	if($model->organization_id){
                		$req = \app\models\Requisite::findOne($model->organization_id);
                		if($req){
                			$banks = $req->banksMap();
                		}
                	}

                ?>
                <?= $form->field($model, 'bank', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
				                    'data' => $banks,
				                    'options' => [
				                        'placeholder' => 'Выберите',
				                        'disabled' => $managerReadonlyDateTimeTop,
				                    ],
				                    'pluginOptions' => [
				                    	'allowClear' => true,
				                    ],
				                ]) ?>

				            
<?php
				            $clientData2 = [];
							$clientQuery = array_filter($clients, function($client){
								return mb_stripos($client->users, '"'.Yii::$app->user->getId().'"');
							});
					

				            if($model->zakazchik_id){
				            	// $clientQuery->orWhere(['id' => $model->zakazchik_id]);
								$clientQuery = array_filter($clients, function($client) use($model){
									return $client->id == $model->zakazchik_id;
								});
				            }

				            if($model->carrier_id){
				            	// $clientQuery->orWhere(['id' => $model->carrier_id]);
								$clientQuery = array_filter($clients, function($client) use($model){
									return $client->id == $model->carrier_id;
								});
				            }

				            foreach ($clientQuery as $client) {
				                $clientData2[$client->id] = $client->name." ({$client->inn})";
				            }



				            ?>         


				<div class="col-md-3">
<?php

$salary = \app\models\Salary::find()->one();
$zakazchik = \app\models\Client::findOne($model->zakazchik_id);
$limit = null;
if($zakazchik){
    $sum = \app\models\Flight::find()->where(['zakazchik_id' => $model->zakazchik_id])->andWhere(['!=', 'is_payed', 1])->sum('we');
    if($zakazchik->limit){
        $limit = $zakazchik->limit;
    } else {
        $salary = \app\models\Salary::find()->one();
        if($salary){
            $limit = $salary->limit;
        }
    }
}
?>
					<div class="form-group">
						<label>Лимит</label>
						<input type="text" class="form-control" name="" readonly="" value="<?= $limit ?>">
					</div>
				</div>


				<?= $form->field($model, 'transit', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
				                    'data' => $clientData2,
				                    'options' => [
				                        'placeholder' => 'Выберите',
				                        'disabled' => $managerReadonlyDateTimeTop,
				                    ],
				                    'pluginOptions' => [
				                    	'allowClear' => true,
				                    ],
				                ]) ?>


                <div class="col-md-3"></div>

				           <?php

				           	$driverData = [];

							$cache = \Yii::$app->cache;

							$drivers = $cache->get('flight_drivers_auto_list');
							if($drivers == null){
								$drivers = \app\models\Driver::find()->select(['id', 'data_avto', 'car_number','car_truck_number'])->all();
								$cache->set('flight_drivers_auto_list', $drivers);
							}

				           	foreach ($drivers as $driver) {
				           		$driverData[$driver->id] = "{$driver->data_avto} {$driver->car_number} {$driver->car_truck_number}"; 
				           	}

				           ?>
                <?= $form->field($model, 'auto', ['cols' => 3, 'colsOptionsStr' => " "])->widget(Select2::class, [
                            'data' => $driverData,
                            'options' => [
                                'placeholder' => 'Выберите',
                                'disabled' => $managerReadonlyDateTimeTop,
                            ],
                        ]) ?>
			    	</div>
			    </div>
			</div>
        </div>




        <div class="col-md-8">
        	<div class="row">
	             <?= $form->field($model, 'rout', ['cols' => 2, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>
	             
		         <?= $form->field($model, 'distance', ['cols' => 2, 'colsOptionsStr' => " "])->textInput(['readonly' => true,])->label('Расстояние (км)')  ?>
	                    
	             <?= $form->field($model, 'contract_place', ['cols' => 3, 'colsOptionsStr' => " "])->textInput()  ?>
	                    
	             <?= $form->field($model, 'date', ['cols' => 2, 'colsOptionsStr' => " "])->input('date', ['readonly' => $managerReadonlyDateTimeTop])  ?>

	             <div class="col-md-3">
	             	<div class="row">
	             		<?php if(\Yii::$app->user->identity->can('flight_order')): ?>
	             			<?= $form->field($model, 'order', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()->label('Заявка')  ?>
	             		<?php endif; ?>
	             		<?php if(\Yii::$app->user->identity->can('flight_driver_order')): ?>
	             			<?= $form->field($model, 'driver_order', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()  ?>
	             		<?php endif; ?>
	             		<?= $form->field($model, 'flights_count', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()  ?>
	             	</div>
	             </div>

	             
        	</div>
        	<div class="row">
        		<div class="col-md-12">
					<div class="panel panel-default">
					    <div class="panel-heading">
					        <h4 class="panel-title">Приоритетный показ</h4>
					    </div>
					    <div class="panel-body">
					    	<?= $form->field($model, 'priority_rate', ['cols' => 2, 'colsOptionsStr' => " "])->textInput()  ?>

					    	<?= $form->field($model, 'priority_limit', ['cols' => 2, 'colsOptionsStr' => " "])->textInput()  ?>

					    	<?= $form->field($model, 'priority_daily_limit', ['cols' => 2, 'colsOptionsStr' => " "])->textInput()  ?>

					    	<?= $form->field($model, 'only_for_paid_users', ['cols' => 6, 'colsOptionsStr' => " style='margin-top: 15px;' "])->checkbox()  ?>
					    </div>
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

             					<?= $form->field($model, 'address1', ['cols' => 6, 'colsOptionsStr' => " "])->textarea(['rows' => 1])  ?>
								
                      <?= $form->field($model, 'shipping_date', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => $managerReadonlyDateTime])  ?>
             					
             			<?php if(\Yii::$app->user->identity->can('flight_dates')): ?>
	                      <?= $form->field($model, 'shipping_date_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'readonly' => $managerReadonlyDateTime])->label(false)  ?>

             			<?php endif; ?>

					            <?= $form->field($model, 'address_out4', ['cols' => 6, 'colsOptionsStr' => " "])->textarea(['rows' => 1])  ?>

             					<?= $form->field($model, 'date_out4', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => $managerReadonlyDateTime])  ?>

             					<?php if(\Yii::$app->user->identity->can('flight_dates')): ?>
                      <?= $form->field($model, 'date_out4_2', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['style' => 'margin-top: 22px;', 'readonly' => $managerReadonlyDateTime])->label(false)  ?>
                      	<?php endif; ?>

                      			<div style="display: none;">
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
        		</div>
        		<div class="col-md-4">
					<div class="panel panel-default">
					    <div class="panel-heading">
					        <h4 class="panel-title">Контакты</h4>
					    </div>
					    <div class="panel-body">
							<div class="row">
            					<?= $form->field($model, 'telephone1', ['cols' => 12, 'colsOptionsStr' => " "])->textarea(['rows' => 1])  ?>

            					<?= $form->field($model, 'telephone', ['cols' => 12, 'colsOptionsStr' => " "])->textarea(['rows' => 1])  ?>

            					<div style="display: none;">
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
        	<div class="row">
        		 

        		<div class="col-md-4">
        			<div class="row">
		        		<?= $form->field($model, 'we', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>
		        		<?= $form->field($model, 'we_prepayment_form', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTime])->label('Предоплата')  ?>
        			</div>
        		</div>

         <?= $form->field($model, 'pay_us', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::pay_usLabels(), ['prompt' => 'Выберите вариант', 'disabled' => $managerReadonlyDateTimeTop]) ?>
         <?php
          	// echo $form->field($model, 'payment1', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::payment1Labels(), ['prompt' => 'Выберите вариант', 'disabled' => $managerReadonlyDateTime]);

			$payment1Data = app\models\Flight::payment1Labels();

			//$paymentTypes = ArrayHelper::getColumn(\app\models\PaymentTypeClient::find()->select(['name'])->all(), 'name');
			//$paymentTypes = array_combine($paymentTypes, $paymentTypes);

			//$payment1Data = ArrayHelper::merge($payment1Data, $paymentTypes);


          	echo $form->field($model, 'payment1', ['cols' => 3, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
          		'data' => $payment1Data,
          		'options' => [
          			'placeholder' => 'Выберите вариант',
          			'disabled' => $managerReadonlyDateTimeTop,
          		],
          		// 'pluginOptions' => [
          		// 	'tags' => true,
          		// ],
          	])

          	?>


             <?= $form->field($model, 'col2', ['cols' => 2, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTime])  ?>


             <div class="col-md-4">
             	<div class="row">
             		<?= $form->field($model, 'payment_out', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>
             		<?= $form->field($model, 'payment_out_prepayment_form', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])->label('Предоплата')  ?>
             	</div>
             </div>


                    
         <?= $form->field($model, 'otherwise2', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::otherwise2Labels(), ['prompt' => 'Выберите вариант', 'disabled' => $managerReadonlyDateTimeTop]) ?>
      
                    
         <?php 

         // echo $form->field($model, 'otherwise3', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::otherwise3Labels(), ['prompt' => 'Выберите вариант', 'disabled' => $managerReadonlyDateTime]) 

		 $otherwiseData = app\models\Flight::otherwise3Labels();

		 //$paymentTypes = ArrayHelper::getColumn(\app\models\PaymentTypeDriver::find()->select(['name'])->all(), 'name');
		 //$paymentTypes = array_combine($paymentTypes, $paymentTypes);

		 //$otherwiseData = ArrayHelper::merge($otherwiseData, $paymentTypes);


         	echo $form->field($model, 'otherwise3', ['cols' => 3, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
				'data' => $otherwiseData,
          		'options' => [
          			'placeholder' => 'Выберите вариант',
          			'disabled' => $managerReadonlyDateTimeTop,
          		],
          		// 'pluginOptions' => [
          		// 	'tags' => true,
          		// ],
         	]);

         ?>

             <?= $form->field($model, 'col1', ['cols' => 2, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>

             <?= $form->field($model, 'act', ['cols' => 3, 'colsOptionsStr' => " "])->textInput()  ?>

             <?= $form->field($model, 'act_date', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => $managerReadonly])  ?>

             <?= $form->field($model, 'daks_balance', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => !\Yii::$app->user->identity->isSuperAdmin()])  ?>

             <div class="col-md-3">
             	<div class="row">
             		<?= $form->field($model, 'delta', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => !\Yii::$app->user->identity->isSuperAdmin()])  ?>
             		<?= $form->field($model, 'delta_percent', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => !\Yii::$app->user->identity->isSuperAdmin()])  ?>
             	</div>
             </div>
             
             <?= $form->field($model, 'salary', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => !\Yii::$app->user->identity->isSuperAdmin()])  ?>









             <div class="col-md-12">
             	<div id="panel-ensurance" class="panel panel-default">
             		<div class="panel-heading">
             			<h4 class="panel-title">Страховка</h4>
             		</div>
             		<div class="panel-body" style="<?= $model->is_insurance == false ? "display: none;" : "" ?>">
             			<div class="row">
             				<?= $form->field($model, 'ensurance_number', ['cols' => 2, 'colsOptionsStr' => " "])->textInput()  ?>
             				<?= $form->field($model, 'ensurance_date', ['cols' => 2, 'colsOptionsStr' => " "])->input('date')  ?>
             				<?= $form->field($model, 'ensurance_organization', ['cols' => 8, 'colsOptionsStr' => " "])->textInput()  ?>
             				<?php
             					$ensuranceCompanies = [];
             					if($model->organization_id){
             						$ensuranceCompanies =\app\models\RequisiteEnsurance::find()->select(['id', 'name'])->where(['requisite_id' => $model->organization_id])->all();
             					}
             				?>
             				<?= $form->field($model, 'ensurance_contract', ['cols' => 5, 'colsOptionsStr' => " "])->textInput()?>
             				
             				<?= $form->field($model, 'ensurance_contract_where', ['cols' => 5, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
             					'data' => ArrayHelper::map($ensuranceCompanies, 'id', 'name'),
             					'options' => [
             						'placeholder' => 'Выберите',
             					],
             					'pluginOptions' => [
             						'allowClear' => true,
             					],
             				])  ?>
             				<?= $form->field($model, 'ensurance_currency', ['cols' => 2, 'colsOptionsStr' => " "])->textInput()  ?>
<?php

$transportData = [];



foreach($clients as $client)
{
	$transportData[$client->id] = "{$client->name} ({$client->inn})";	
}

?>
             				<?= $form->field($model, 'ensurance_client', ['cols' => 6, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
             					'data' => $transportData,
             					'options' => [
             						'placeholder' => 'Выберите',
             					],
             				])  ?>

             				<?= $form->field($model, 'ensurance_profit_subject', ['cols' => 6, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [

             					'data' => $transportData,
             					'options' => [
             						'placeholder' => 'Выберите',
             					],
             				])  ?>
             				<div class="col-md-2">
             					<div class="form-group">
             						<label>Стоимость груза</label>
             						<input id="namePrice" type="text" class="form-control" value="<?= $model->name_price ?>" disabled="">
             					</div>
             				</div>
             				<?= $form->field($model, 'ensurance_prime', ['cols' => 2, 'colsOptionsStr' => " "])->textInput()  ?>
             				<div class="col-md-8"></div>
             				<?= $form->field($model, 'ensurance_transport_type', ['cols' => 12, 'colsOptionsStr' => " "])->dropDownList([
             					'Автомобильным' => 'Автомобильным',
             					'Железнодорожным' => 'Железнодорожным',
             					'Воздушным' => 'Воздушным',
             					'Мультимодальным (смешанным)' => 'Мультимодальным (смешанным)',
             					'Водным' => 'Водным',
             				])  ?>
             				<?= $form->field($model, 'ensurance_subject_from', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()  ?>
             				<?= $form->field($model, 'ensurance_subject_to', ['cols' => 6, 'colsOptionsStr' => " "])->textInput()  ?>
             				<?php
						        $conditions = [];
						        if($model->ensurance_contract_where){
							        $requisiteEnsurance = \app\models\RequisiteEnsurance::findOne($model->ensurance_contract_where);
							        if($requisiteEnsurance){
							            $items = \app\models\RequisiteEnsurance::find()->select(['id', 'condition', 'percent'])->where(['name' => $requisiteEnsurance->name])->all();

							            foreach($items as $item)
							            {
							                if(isset($conditions[$item->condition]) == false && $item->percent){
							                    $conditions[$item->percent] = $item->condition;
							                }
							            }
							        }
						        }

								if(count($conditions) == 0 && $model->ensurance_condition != null){
									$conditionsItem = \app\models\RequisiteEnsurance::findOne($model->ensurance_condition);
									if($conditionsItem){
										$conditions[$model->ensurance_condition] = $conditionsItem->condition;
									}
								}

             				?>
             				<?= $form->field($model, 'ensurance_condition', ['cols' => 10, 'colsOptionsStr' => " "])->widget(\kartik\select2\Select2::class, [
             					'data' => $conditions,
             					'options' => [
             						'placeholder' => 'Выберите',
             					],
             					'pluginOptions' => [
             						'allowClear' => true,
             					],
             				])  ?>
             				<?= $form->field($model, 'ensurance_percent', ['cols' => 2, 'colsOptionsStr' => " "])->textInput()  ?>
             				<?= $form->field($model, 'ensurance_ref', ['cols' => 6, 'colsOptionsStr' => " "])->checkbox()  ?>
             				<?= $form->field($model, 'ensurance_security', ['cols' => 6, 'colsOptionsStr' => " "])->checkbox()  ?>
             				<?= $form->field($model, 'ensurance_additional', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
             				<?= $form->field($model, 'ensurance_order', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>
             				<?= $form->field($model, 'ensurance_comment', ['cols' => 12, 'colsOptionsStr' => " "])->textInput()  ?>

             			</div>
             		</div>
             	</div>
             </div>


        	</div>
        </div>
        <div class="col-md-4">
      
			<div class="panel panel-default">
			    <div class="panel-heading">
			        <h4 class="panel-title">Данные о перевозке</h4>
			    </div>
			    <div class="panel-body">
			    	<div class="row">
			         <?= $form->field($model, 'view_auto', ['cols' => 12, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::view_autoLabels(), ['prompt' => 'Выберите вариант', 'disabled' => false]) ?>
			      

		             <?= $form->field($model, 'body_type', ['cols' => 12, 'colsOptionsStr' => " "])->widget(Select2::class, [
		             	'data' => \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/cars.php'), 'TypeId', 'Name'),
		             	'options' => [
		             		'placeholder' => 'Выберите',
		             	],
		             	'pluginOptions' => [
		             		'allowClear' => true,
		             	],
		             ])  ?>

		             <?= $form->field($model, 'loading_type', ['cols' => 6, 'colsOptionsStr' => " "])->widget(Select2::class, [
		             	'data' => \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/loading-types.php'), 'Id', 'Name'),
		             	'options' => [
		             		'placeholder' => 'Выберите',
		             	],
		             	'pluginOptions' => [
		             		'allowClear' => true,
		             	],
		             ])  ?>
		             
		             <?= $form->field($model, 'uploading_type', ['cols' => 6, 'colsOptionsStr' => " "])->widget(Select2::class, [
		             	'data' => \yii\helpers\ArrayHelper::map(require(__DIR__.'/../../data/loading-types.php'), 'Id', 'Name'),
		             	'options' => [
		             		'placeholder' => 'Выберите',
		             	],
		             	'pluginOptions' => [
		             		'allowClear' => true,
		             	],
		             ])  ?>

					<?php
						$namesList = \app\models\Flight::namesList();
						$namesList = array_combine($namesList, $namesList);
					?>
		             <?= $form->field($model, 'name', ['cols' => 6, 'colsOptionsStr' => " "])->widget(Select2::class, [
		             	'data' => $namesList,
		             	'options' => [
		             		'placeholder' => 'Выберите',
		             	],
		             	'pluginOptions' => [
		             		'allowClear' => true,
		             	],
		             ])  ?>

		             <?= $form->field($model, 'name_price', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

		             <?= $form->field($model, 'volume', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            		 <?= $form->field($model, 'length', ['cols' => 4, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            		 <?= $form->field($model, 'width', ['cols' => 4, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
            		 
            		 <?= $form->field($model, 'height', ['cols' => 4, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
            		 
            		 <?= $form->field($model, 'diameter', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            		 <?= $form->field($model, 'cargo_weight', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>
            		 
            		 <?= $form->field($model, 'type_weight', ['cols' => 3, 'colsOptionsStr' => " "])->dropDownList(app\models\Flight::typeWeightLabels(), ['prompt' => "Выберите"])  ?>
            		 
            		 <?= $form->field($model, 'place_count', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            		 <?= $form->field($model, 'belts_count', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => false])  ?>

            		 <?= $form->field($model, 'logging_truck', ['cols' => 4, 'colsOptionsStr' => " "])->checkbox()  ?>

            		 <?= $form->field($model, 'road_train', ['cols' => 4, 'colsOptionsStr' => " "])->checkbox()  ?>

            		 <?= $form->field($model, 'air_suspension', ['cols' => 4, 'colsOptionsStr' => " "])->checkbox()  ?>


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

		             <?= $form->field($model, 'dop_informaciya_o_gruze', ['cols' => 12, 'colsOptionsStr' => " "])->textarea(['rows' => 1])  ?>
			    	</div>
			    </div>
			</div>
        </div>

                
                    
                    

                    



                    
                    
                    




                    

                    
             

                    










      
                    

                    


            

        <div class="col-md-12">
			<div class="panel panel-default">
			    <div class="panel-heading">
			        <!--        <div class="panel-heading-btn">-->
			        <!--        </div>-->
			        <h4 class="panel-title">Счет</h4>
			    </div>
			    <div class="panel-body">
			    	<div class="row">
			    		<div class="col-md-3">
			    			<div class="row">
						    		<?= $form->field($model, 'fio', ['cols' => 6, 'colsOptionsStr' => " "])->dropDownList([
						                'Стандарт' => 'Стандарт',
						                'Срыв погрузки' => 'Срыв погрузки',
						                //'Предоплата' => 'Предоплата',
						                'Авансовый' => 'Авансовый',
						                'Ваш текст' => 'Ваш текст',
						                'Международные' => 'Международные',
						             ], ['prompt' => 'Выберите'])  ?>
			    					<?= $form->field($model, 'bill_type_prepayed', ['cols' => 6, 'colsOptionsStr' => " "])->dropDownList([
						                'Предоплата' => 'Предоплата',
						                //'Ваш текст' => 'Ваш текст',
						             ], ['prompt' => 'Выберите'])  ?>
			    			</div>
			    		</div>
			             


			             <?= $form->field($model, 'date_cr', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => $managerReadonlyDateTimeTop])  ?>

			             <?= $form->field($model, 'number', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>

			             <?= $form->field($model, 'upd', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>

			             <?= $form->field($model, 'otherwise4', ['cols' => 3, 'colsOptionsStr' => " "])->textarea(['rows'=>6,'readonly' => $managerReadonly])  ?>

			             <?= $form->field($model, 'date_cr_prepayed', ['cols' => 3, 'colsOptionsStr' => " "])->input('date', ['readonly' => $managerReadonlyDateTimeTop])  ?>
			             <?= $form->field($model, 'number_prepayed', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>
			             
                                     <?php //= $form->field($model, 'upd_prepayed', ['cols' => 3, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>
                                    <div class="col-md-3" style="min-height: 72px"></div>



			             <?= $form->field($model, 'otherwise', ['cols' => 3, 'colsOptionsStr' => " "])->textarea(['readonly' => $managerReadonly])  ?>

			             <div class="col-md-3">
			             	<div class="row">
			             		<?= $form->field($model, 'recoil', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>
			             		<?= $form->field($model, 'additional_credit', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => $managerReadonlyDateTimeTop])  ?>
			             	</div>
			             </div>


			             <?= $form->field($model, 'your_text', ['cols' => 3, 'colsOptionsStr' => " "])->textarea()  ?>
			             

			             <div class="col-md-12">
			             	
			             </div>
			                    
			            <?php $readonly = !\Yii::$app->user->identity->can('mail_update') ? true : false; ?> 
                                    
			             <div class="col-md-3">
			             	<div class="row">
                                            <?php /*
				             	<?= $form->field($model, 'date2', ['cols' => 6, 'colsOptionsStr' => " "])->input('date', ['readonly' => $readonly])  ?>
			             		<?= $form->field($model, 'track_number', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => $readonly])  ?>
                                            */ ?>
                                        </div>
			             	<div class="row">
			             		<?= $form->field($model, 'letter_info', ['cols' => 12, 'colsOptionsStr' => " "])->textInput(['readonly' => $readonly])  ?>
			             	</div>

			             </div>

			             <div class="col-md-3">
			             	<div class="row">
                                            <?php /*
				            	<?= $form->field($model, 'date3', ['cols' => 6, 'colsOptionsStr' => " "])->input('date', ['readonly' => $readonly])  ?>
				            	<?= $form->field($model, 'track_number_driver', ['cols' => 6, 'colsOptionsStr' => " "])->textInput(['readonly' => $readonly])  ?>
                                            */ ?>
                                        </div>
			             	<div class="row">
			             		<?= $form->field($model, 'letter_info_driver', ['cols' => 12, 'colsOptionsStr' => " "])->textarea(['readonly' => $readonly,'rows' => 1]) ?>
			             	</div>

			             </div>

		                    
			             	<?= $form->field($model, 'name3', ['cols' => 3, 'colsOptionsStr' => " "])->textarea(['rows' => 1])->label('Информацию по рейсу')  ?>



			             		<?= $form->field($model, 'info_from_client', ['cols' => 3, 'colsOptionsStr' => " "])->textarea(['rows' => 1]) ?>


							 <?= $form->field($model, 'client_comment', ['cols' => 3, 'colsOptionsStr' => " "])->textarea(['rows' => 1]) ?>


					</div>
			    </div>
			</div>
        </div>



                    
              <?php if(\Yii::$app->user->identity->isSuperAdmin()): ?>
             	<?= $form->field($model, 'is_register', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox()  ?>
              <?php else: ?>
	             <?= $form->field($model, 'is_register', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox(['disabled' => boolval($model->is_register)])  ?>
              <?php endif; ?>
             <?php if(\Yii::$app->user->identity->can('flight_is_order')): ?>
             <?= $form->field($model, 'is_order', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox()  ?>
             <?php endif; ?>
				<?php if(false): ?>
					<?php if((\Yii::$app->user->identity->can('flight_is_signature') && $group == false) || (\Yii::$app->user->identity->can('flight_is_signature') && $group == true && \Yii::$app->user->identity->can('flight_group_table'))): ?>
             <?= $form->field($model, 'is_signature', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox()  ?>
             <?php endif; ?>
             <?php if((\Yii::$app->user->identity->can('flight_driver_signature') && $group == false) || (\Yii::$app->user->identity->can('flight_driver_signature') && $group == true && \Yii::$app->user->identity->can('flight_group_table'))): ?>
			<?= $form->field($model, 'is_driver_signature', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox()  ?>
			 <?php endif; ?>


				<?php endif; ?>


			 	<?= $form->field($model, 'is_insurance', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox(['disabled' => (boolean) $model->is_register]) ?>

				<?= $form->field($model, 'is_delivery_document', ['cols' => 2, 'colsOptionsStr' => " "])->checkbox() ?>
			 
			 	<?= $form->field($model, 'is_register_letter', ['cols' => 4, 'colsOptionsStr' => " "])->checkbox() ?>
			 	<?= $form->field($model, 'is_register_letter_driver', ['cols' => 4, 'colsOptionsStr' => " "])->checkbox()  ?>


             <div class="row">
               <div class="col-md-12">
                 
               </div>
             </div>

                    
                    
<?php /*                     
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
        var strE = path.url;        
		//var ext = strE.substring(strE.lastIndexOf('.')+1);
        var ext = path.ext;
		if(arr.includes(ext)){
          strE = path.url;
        } else {
          strE = 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';
        }
        var html = '<tr class=\"template-upload fade in\"><td><span class=\"preview\"><img src=\"'+strE+'\" style=\"width: 50px;\"></span></td>';
        html += '<td><p class=\"name\">'+path.name+'</p><p class=\"size\">'+path.size+'</p></td>';
        html += '<td><a data-delete=\"'+path.url+'\" class=\"btn btn-danger cancel\"><i class=\"glyphicon glyphicon-ban-circle\"></i><span>Удалить</span></a></td></tr>';
        $('.files').append(html);
        $('.files [data-delete]').unbind('click');
        $('.files [data-delete]').click(deleteEventHandler);
        $('.files [data-delete]').on('keydown', keyDownEventHandler);
      }",
     ],
]);?>
<div style="display: none;">
  <input type="hidden" name="file_file_path">
</div>
<?php
$script = <<< JS


var keyDownEventHandler = function(e){
   if(e.keyCode === 13){
       e.preventDefault();
   }
};



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
$('.files [data-delete]').on('keydown', keyDownEventHandler);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style="height: 200px; overflow: auto;">
                    <table role="presentation" class="table table-striped">
                        <tbody class="files">
                        <?php if ($model->file):
								$counter = 0; 
								$files = json_decode($model->file, true);
								if($files):
								foreach ($files as $value):?> 
							<?php if(isset($value['url']) == false) { continue; } ?>
                          <tr class="template-upload fade in">
                                <td>
                                    <span class="preview">
                                      <img src="<?php if(file_exists(substr($value['url'], 1)) !== false or strpos($value['url'],'base64')){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
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
                                <a data-delete="<?=$value['url']?>" class="btn btn-danger cancel" onclick="deleteEventHandler">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Удалить</span>
                                </a>
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
						<?php endif; ?>
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
        html += '<td><a data-delete=\"'+path.url+'\" class=\"btn btn-danger cancel\"><i class=\"glyphicon glyphicon-ban-circle\"></i><span>Удалить</span></a></td></tr>';
        $('.files-provider').append(html);
        $('.files-provider [data-delete]').unbind('click');
        $('.files-provider [data-delete]').click(deleteEventHandlerProvider);
        $('.files-provider [data-delete]').on('keydown', keyDownEventHandler);
      }",
     ],
]);?>
<div style="display: none;">
  <input type="hidden" name="file_provider_file_path">
</div>
<?php
$script = <<< JS


var keyDownEventHandler = function(e){
   if(e.keyCode === 13){
       e.preventDefault();
   }
};

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
$('.files-provider [data-delete]').on('keydown', keyDownEventHandler);


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
                </div>

                <div class="col-md-7" style="height: 200px; overflow: auto;">
                    <table role="presentation" class="table table-striped">
                        <tbody class="files-provider">
                        <?php if ($model->file_provider):?>
                        <?php  $counter = 0; $files=json_decode($model->file_provider, true);
						if($files):
						foreach ($files as $value):?>
                        <?php if(isset($value['url']) == false) { continue; } ?>
                          <tr class="template-upload fade in">
                                <td>
                                    <span class="preview">
                                      <img src="<?php if(file_exists(substr($value['url'], 1)) !== false or strpos($value['url'],'base64')){echo $value['url'];}else{echo 'https://shopmalinka.ru/image.php?main=images/product_images/popup_images/000206844_s.jpg';}?>" style="width: 50px;">
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
                                <a data-delete="<?=$value['url']?>" class="btn btn-danger cancel" onclick="deleteEventHandlerProvider">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Удалить</span>
                                </a>
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
						<?php endif; ?>
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
*/ ?>
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



$("#flight-fio").change(function(){
    let value = $(this).val();
	let flight_order = $('#flight-order').val();
	let date_cr = $('#flight-date_cr').val();

	if(date_cr==''){
		alert('Не указана "Дата УПД\Счет"'); 
	}
	if(flight_order==''){
		alert('Не указан номер "Заявка"'); 
	}	

	if(value == 'Стандарт' && date_cr && flight_order){

		let [, year, month, day] = /(\d{4})-(\d{1,2})-(\d{1,2})/.exec(date_cr);

		$('#flight-number').val(flight_order);
		$('#flight-upd').val(flight_order);
    }


});


$("#flight-bill_type_prepayed").change(function(){
    let value = $(this).val();
    let date_cr_prepayed = $('#flight-date_cr_prepayed').val();
	let flight_order = $('#flight-order').val();

	if(date_cr_prepayed==''){
		alert('Не указана "Дата УПД\Счет аванс(предоплата)"'); 
	}

	if(flight_order==''){
		alert('Не указан номер "Заявка"'); 
	}	

	if(value == 'Предоплата' && date_cr_prepayed && flight_order){

		let [, year, month, day] = /(\d{4})-(\d{1,2})-(\d{1,2})/.exec(date_cr_prepayed);

		$('#flight-number_prepayed').val('А'+flight_order);
		//$('#flight-upd_prepayed').val('Б'+flight_order+' от '+day+'.'+month+'.'+year);
    }

});

$("#flight-date_cr_prepayed").on( "change", function() {
	document.getElementById('flight-bill_type_prepayed').value="";
	$('#flight-number_prepayed').val('');
	//$('#flight-upd_prepayed').val('');
});


$("#flight-date_cr").on( "change", function() {
	document.getElementById('flight-bill_type').value="";
	$('#flight-number').val('');
	$('#flight-upd').val('');
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

<?php

$script = <<< JS

$("#flight-upd").change(function(){
	var value = $(this).val();
	if(value){
		$.get("/mail/get-by-upd?upd="+$(this).val(), function(response){
			if(response.model){
				$("#flight-track_number_driver").val(response.model.track);
				$("#flight-date3").val(response.model.when_send);
			}
		});
	}
});

$(document).ready(function(){
	$('#flight-pay_us, #flight-otherwise2').change(function(){
		var value = $(this).val();

		if(value == 'Наличными' || value == 'на карту'){
			$('#flight-number, #flight-upd').val('Нал');
		}
	});

	$('#flight-date_out4').change(function(){
		var value = $(this).val();
		var value2 = $('#flight-date_out4_2').val();

		console.log('here');
		console.log(value2);

		if(value2 == ''){
			$('#flight-date_cr').val(value);
		}
	});

	
	$('#flight-date_out4_2').change(function(){
		var value = $(this).val();
		$('#flight-date_cr').val(value);
	});
});

$('#flight-organization_id').change(function(){
	var value = $(this).val();

	// $.get('/requisite/get-bank?id='+value, function(response){
	// 	if(response.id){
	// 		$('#flight-payment_bank_id').val(response.id);
	// 		$('#flight-payment_bank_id').trigger("change");
	// 	}
	// });

	$.get("/requisite/bank-map?id="+value, function(response){
		$("#flight-bank").html(response.data);
		$("#flight-bank").trigger("change");
	});

	var organizationName = $("#flight-organization_id option[value="+value+"]").text();

	if($('#flight-is_insurance').is(':checked')){
		$('#flight-ensurance_organization').val(organizationName);

		$.get("/requisite/ensurance-map?id="+value, function(response){
			$('#flight-ensurance_contract_where').html(response.data);
			// $('#flight-ensurance_contract').html(response.data3);
			$('#flight-ensurance_condition').html(response.data2);
		});
	}

});

$("#flight-ensurance_contract_where").change(function(){
	var id = $(this).val();

	$.get("/requisite/get-ensurance-by-name?id="+id, function(response){ 
	    if(response.model){
	        $("#flight-ensurance_contract").val(response.model.contract);
	        // $("#flight-ensurance_condition").val(response.model.condition);
	        // $("#flight-ensurance_percent").val(response.model.percent).trigger("change");
	    }
	    if(response.conditions){
	    	var options = '<option value="">Выберите</option>';
	    	$.each(response.conditions, function(key, value){
	    		options = options + '<option value="'+value+'">'+key+'</option>';
	    	});
	    	$("#flight-ensurance_condition").html(options);
	    }
	 }); 
});

$("#flight-ensurance_condition").change(function(){
	var value = $(this).val();
	$("#flight-ensurance_percent").val(value);
});


$("#flight-ensurance_condition").change(function(){
	var id = $(this).val();

	$.get("requisite/get-ensurance-by-id?id="+id, function(response){ 
	    if(response.model){
	        // $("#flight-ensurance_contract").val(response.model.contract);
	        // $("#flight-ensurance_condition").val(response.model.condition);
	        $("#flight-ensurance_percent").val(response.model.percent).trigger("change");
	    }
	 }); 
});

$("#flight-fio").change(function(){
	var value = $(this).val();

	if(value == 'Срыв погрузки'){
		$('#flight-pay_us').val('без НДС');
		$('#flight-otherwise2').val('без НДС');
	}
});


$("#flight-ensurance_percent, #flight-name_price").change(function(){
	var percent = $('#flight-ensurance_percent').val();
	var sum = $('#flight-name_price').val();
	var sum2 = sum * percent / 100;
	console.log(sum2, 'sum2');
	$("#flight-ensurance_prime").val(sum2);

	$('#namePrice').val(sum);
});

$('#flight-date, #flight-order').change(function(){
	var order = $('#flight-order').val();
	var date = $('#flight-date').val().split('-').reverse().join('.');

	var str = "Новый к заявке №"+order+" от "+date+" г.";

	$("#flight-ensurance_additional").val(str);
});




$("#flight-is_insurance").change(function(){
	var value = $(this).is(":checked");

	if(value){
		$("#panel-ensurance .panel-body").slideDown();
		$('#flight-organization_id').trigger("change");
		$("#flight-order").trigger("change");
	} else {
		$("#panel-ensurance .panel-body").slideUp();
	}
});





$("#flight-we, #flight-payment_out, #flight-recoil, #flight-additional_credit").change(function(){
	var we = $('#flight-we').val();
	var payment_out = $('#flight-payment_out').val();
	var recoil = $('#flight-recoil').val();
	var additional_credit = $('#flight-additional_credit').val();
});


$("#flight-we, #flight-payment_out, #flight-recoil, #flight-daks_balance, #flight-delta, #flight-delta_percent").change(function(){
	$.ajax({
		url: "/flight/calculate-salary",
		method: "POST",
		data: $(".modal-body form").serialize(),
		success: function(response){
			$("#flight-salary").val(reponse.salary);
		},
	});
});

$('#flight-is_register').change(function(){
	if($(this).is(':checked')){
		$('#flight-is_insurance').attr('disabled', true);
	} else {
		$('#flight-is_insurance').removeAttr('disabled');
	}
});

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>