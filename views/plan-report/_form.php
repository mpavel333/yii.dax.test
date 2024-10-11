<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;



/* @var $this yii\web\View */
/* @var $model app\models\Flight */
/* @var $form yii\widgets\ActiveForm */
/*
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

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
                                               

        <?php
        //$requisiteData = [];
        //foreach (\app\models\Requisite::find()->where(['is_hidden' => false])->all() as $requisite) {
        //    $requisiteData[$requisite->id] = $requisite->name." ({$requisite->inn}) ".$requisite->bank_name;
        //}

        ?>


        <div class="col-md-12">
			<div class="panel panel-default">
			    <div class="panel-heading">
			        <!--        <div class="panel-heading-btn">-->
			        <!--        </div>-->
			        <h4 class="panel-title"></h4>
			    </div>
			    <div class="panel-body">
			    	<div class="row">


					<?= $form->field($model, 'total_summ', ['cols' => 2, 'colsOptionsStr' => " "])->textInput()->label('Общая сумма в мес., руб. Мин. сумма')  ?>
					

					</div>
				</div>		
																		

			</div>
		</div>

	</div>
                    
    
<?php
$script = <<< JS


var keyDownEventHandler = function(e){
   if(e.keyCode === 13){
       e.preventDefault();
   }
};

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>
         


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>


<div style="display: none;">
	<div id="map" style="width: 500px; height: 500px;">
	
	</div>
	<div id="viewContainer"></div>
</div>