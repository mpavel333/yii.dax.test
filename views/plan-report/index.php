<?php

use \yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset; 
use yii\widgets\ActiveForm;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\dynagrid\DynaGrid;
use app\components\MyCache;
use kartik\select2\Select2;

use app\models\Flight;

$this->title = "План отчет";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<style type="text/css">
	.modal-dialog {
		width: 80% !important;
	}
</style>

<?php /*
<div class="row">

	<div class="col-md-12" style="margin-bottom: 10px;">

	<?php

	echo Html::a(Yii::t('app', "Добавить") .'  <i class="fa fa-plus"></i>', ['plan-report/create'],
	['role'=>'modal-remote','title'=>  Yii::t('app', "Добавить"),'class'=>'btn btn-success']);

	?>

	</div>
 
</div>
*/ ?>


<div class="col-md-12">
	<div class="card card-shadow m-b-10">
		<div class="row">
			<?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'GET', 'action' => [Yii::$app->controller->id.'/index']]) ?>

				<div class="col-md-12">
					<p>
						<?php
							$session = \Yii::$app->session;

							$filtersOpen = false;
							if($session->has('flight-index-filters-open')){
								$filtersOpen = $session->get('flight-index-filters-open');
							}
						
						?>

						<a id="filters-btn-hide" class="pull-right" href="#" onclick="event.preventDefault(); $('#filters-container').hide(); $(this).hide(); $('#filters-btn-show').show(); $.get('/flight/session-toggle-filters?state=0');"<?= $filtersOpen ? null : 'style="display: none;"' ?>><i class="fa fa-minus"></i></a>
						<a id="filters-btn-show" class="pull-right" href="#" onclick="event.preventDefault(); $('#filters-container').show(); $(this).hide(); $('#filters-btn-hide').show(); $.get('/flight/session-toggle-filters?state=1');"<?= $filtersOpen ? 'style="display: none;"' : null ?>><i class="fa fa-plus"></i></a>
					</p>
				</div>

				<div id="filters-container"<?php // $filtersOpen ? null : 'style="display: none;"' ?>>

				<div class="col-md-12">
					<?php 

							//print_r($searchModel);

					?>
					<?php echo $form->field($searchModel, 'date_from', ['cols' => 2, 'colsOptionsStr' => " "])->input('date', [])  ?>
					<?php echo $form->field($searchModel, 'date_to', ['cols' => 2, 'colsOptionsStr' => " "])->input('date', [])  ?>
				
				</div>		

				<div class="col-md-12">
					<hr style="margin-top: 5px; margin-bottom: 15px;">
				</div>

				<div class="col-md-12">
					<div style="float: right;">
						<?= Html::a('Сбросить', ['plan-report/index'], ['class' => 'btn btn-white']) ?>
						<?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>
					</div>
				</div>

			<?php ActiveForm::end() ?>
		</div>
	</div>
</div>    
</div>

<div class="col-md-12">

<?php \yii\widgets\Pjax::begin(['id' => 'crud-datatable-planreport-pjax']) ?>

<div class="card-shadow">
	<div class="planreport_wrap">



	<table class="table">
  	<thead>
	<tr>

		<th>ТС</th>
		<th>Марка машины</th>
		<th>Водитель</th>
		<th>Менеджер</br>ФИО</th>
		<th>Общая сумма в мес., руб.</br>Мин. сумма</th>
		<th>с НДС</th>
		<th>без НДС</th>
		<th>Наличные</th>
		<th>Всего</th>
		<th>%</th>
		<th>Отклонение </br> от общей</th>
		<th>Зарплата диспетчера при не выполнении плана на 10%</br>База зп 1%</th>
		<th>Зарплата диспетчера при выполнении плана</br>База зп 2%</th>
		<th>Зарплата диспетчера при перевыполнении плана на 10%</br>База зп 3%</th>
		<th>Зарплата диспетчера при перевыполнении плана более 10%</br>База зп 4%</th>
		<th>Отчет по дате</br><?php if($searchModel->date_from) echo $searchModel->date_from ?>Р/км факт</th>
		<th>Отчет по дате</br><?php if($searchModel->date_to) echo $searchModel->date_to ?>км</th>
		<th>Зарплата</th>
    </tr>
  </thead>
  <tbody>

  <?php 
 
 $t_total_summ = 0;
 $t_we_nds = 0;
 $t_we_no_nds = 0;
 $t_cash = 0;
 $t_total = 0;

 foreach($dataProvider->getModels() as $model): 

	$we_nds = 0;
	$we_no_nds = 0;
	$cash = 0;
	$total = 0;
	$total_p = 0;

	$distance = 0;
	$distance_fact = 0;

	$salary = 0;



	$driver = $model->getDriver()->asArray()->one();

	if($searchModel->date_from && $searchModel->date_to){
		$flights = $model->getFlight()
		->andWhere(['>=', 'date', date('Y-m-d',strtotime($searchModel->date_from))])
		->andWhere(['<=', 'date', date('Y-m-d',strtotime($searchModel->date_to))])
		->all();
	}elseif($searchModel->date_from){
		$flights = $model->getFlight()->andWhere(['>=', 'date', date('Y-m-d',strtotime($searchModel->date_from))])->all();
	}elseif($searchModel->date_to){
		$flights = $model->getFlight()->andWhere(['<=', 'date', date('Y-m-d',strtotime($searchModel->date_to))])->all();
	}else{
		$flights = $model->getFlight()->all();
	}
	
	foreach($flights as $item):

		if($item['pay_us']=='с НДС') $we_nds += (int) $item['we'];
		if($item['pay_us']=='без НДС') $we_no_nds += (int) $item['we'];
		if($item['pay_us']=='Наличными') $cash += (int) $item['we'];

		$distance += (int) $item['distance'];
		$salary += (int) $item['salary'];

	endforeach;
	
	$total = $we_nds+$we_no_nds+$cash;
	
	if($total && $model->total_summ) 
		$total_p = round(($total/$model->total_summ)*100);

	$deviation = $model->total_summ - $total;

	if($total && $distance)	
		$distance_fact = round($total/$distance);

	

 ?>

  	<tr>

		<td><?=$model->number?></td>
		<td><?=$model->name?></td>
		<td><?=$driver['data'] ?></td>
		<td><?=ArrayHelper::getValue($model, 'user.name') ?></td>

		<td><?=number_format($model->total_summ,0,'',' ')?><?=Html::a('<i class="fa fa-pencil"></i>', ['plan-report/update', 'id' => $model->id], ['class' => 'btn btn-sm btn-white','role' => 'modal-remote', 'title' => 'Редактировать']); ?></td>
		<td><?=number_format($we_nds,0,'',' ')?></td>
		<td><?=number_format($we_no_nds,0,'',' ')?></td>
		<td><?=number_format($cash,0,'',' ')?></td>
		<td><?=number_format($total,0,'',' ')?></td>

		<td><?=$total_p?>%</td>
		<td><?=number_format($deviation,0,'',' ')?></td>

		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>

		<td><?=number_format($distance_fact,0,'',' ')?></td>
		<td><?=number_format($distance,0,'',' ')?></td>
		<td><?=number_format($salary,0,'',' ')?></td>
    </tr>

	<?php

	$t_total_summ += $model->total_summ;
	$t_we_nds +=$we_nds;
	$t_we_no_nds +=$we_no_nds;
	$t_cash +=$cash;
	$t_total +=$total;


	?>

	<?php endforeach; ?>

	<tr>

		<td>ИТОГО</td>
		<td></td>
		<td></td>
		<td></td>

		<td><?=number_format($t_total_summ,0,'',' ')?></td>
		<td><?=number_format($t_we_nds,0,'',' ')?></td>
		<td><?=number_format($t_we_no_nds,0,'',' ')?></td>
		<td><?=number_format($t_cash,0,'',' ')?></td>
		<td><?=number_format($t_total,0,'',' ')?></td>
		<td></td>
		<td></td>

		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>

		<td></td>
		<td></td>
		<td></td>

	</tr>


  </tbody>
</table>


	</div>
</div>



<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
