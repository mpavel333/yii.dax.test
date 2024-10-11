<?php

use \yii\helpers\ArrayHelper;

$rusMonth = [
  'января',
  'февраля',
  'марта',
  'апреля',
  'мая',
  'июня',
  'июля',
  'августа',
  'сентября',
  'октября',
  'ноября',
  'декабря'
];


$I29 = $model->we;

if(is_numeric($I29) == false){
	$I29 = 0;
}

$I28 = round($I29 * 20 / 120, 2);
$I27 = round($I29 - $I28, 2);

?>
<style type="text/css" media="print">
  @page {
  	/*size: landscape;*/
  	size: 13in 10in;
  	/*margin: 1cm;*/
  }
  * {
  	font-size: 9px;
  }
  /*body {*/
  	/*transform: scale(.95);*/
  /*}*/

  @media print {
  	body {
  		padding: 0 !important;
  		margin: 0 !important;
  	}
  }

  .pg-break {
  	page-break-after: always;
  }

<?php if(ArrayHelper::getValue($model, 'organization.nds') == false): ?>
/*	.nds-line {
		display: none;
	}*/
<?php endif; ?>

</style>
<!-- <div style="height: 20px; width: 100%;"></div> -->
<div style="margin-left: 15px;">


<table border="0" cellpadding="0" cellspacing="0" class="no-grid waffle">
	<thead>
	</thead>
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" rowspan="4" style="border-right: 2px solid #000; font-size: 25px; line-height: 30px;">Авансовый УПД</td>
			<td colspan="2">Счет-фактура N</td>
			<td colspan="2" style="text-align: center; border-bottom: 1px solid #000;"><?= $model->number ?></td>
			<td colspan="2" style="text-align: center;">от</td>
			<td colspan="2" style="white-space: nowrap; text-align: center; border-bottom: 1px solid #000;"><?php $month = date('n', strtotime($model->date_cr_prepayed))-1; ?><?= date('d '.$rusMonth[$month].' Y г.', strtotime($model->date_cr_prepayed)) ?></td>
			<td colspan="4">(1)</td>
			<td colspan="10" rowspan="2" style="font-size: 11px; text-align: right;">Приложение № 1 к постановлению Правительства Российской Федерации от 26 декабря 2011 г. № 1137<br />
			(в редакции постановления Правительства Российской Федерации от 2 апреля 2021 г. № 534)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Исправление N</td>
			<td colspan="2" style="text-align: center; border-bottom: 1px solid #000;"></td>
			<td colspan="2" style="text-align: center;">от</td>
			<td colspan="2" style="white-space: nowrap; text-align: center; border-bottom: 1px solid #000;">&nbsp;</td>
			<td colspan="4">(1а)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="35">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:96px;left:-1px">Продавец</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'organization.name') ?></td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(2)</td>
			<td colspan="2">Покупатель</td>
			<td colspan="6" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'zakazchik.name'); ?></td>
			<td colspan="4" style="border-bottom: 1px solid #fff; text-align: left">(6)</td>
			<td>
			<div class="softmerge-inner" style="width:82px;left:-1px"></div>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(6)</td>
		</tr>
		<tr>
		
			<td>&nbsp;</td>
			<td colspan="1">Статус:</td>
			<td colspan="1" style="text-align: center; border: 2px solid #000;"><?= ArrayHelper::getValue($model, 'organization.nds') ? '1' : '2' ?></td>
			<td style="border-right: 2px solid #000;">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1">
			<div class="softmerge-inner" style="width:42px;left:-1px">Адрес</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'organization.official_address') ?></td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(2a)</td>
			<td colspan="2">Адрес</td>
			<td colspan="6" style="border-bottom: 1px solid #000; font-size: 12px; height: 39px;"><?= ArrayHelper::getValue($model, 'zakazchik.official_address') ?></td>
			<td colspan="4" style="border-bottom: 1px solid #fff; text-align: left">(6а)</td>
			<td>
			<div class="softmerge-inner" style="width:17px;left:-1px"></div>
			</td>
			<td>
			<div class="softmerge-inner" style="width:49px;left:-1px"></div>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" rowspan="6" style="border-right: 2px solid #000; vertical-align: initial;"><br />
			1 - счет-фактура и передаточный документ (акт)<br />
			2 - передаточный документ (акт)</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:143px;left:-1px; font-size: 13px;">ИНН/КПП продавца</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'organization.inn') ?> / <?= ArrayHelper::getValue($model, 'organization.kpp') ?></td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(2б)</td>
			<td colspan="2">ИНН/КПП покупателя</td>
			<td colspan="6" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'zakazchik.inn') ?> / <?= ArrayHelper::getValue($model, 'zakazchik.kpp') ?></td>
			<td colspan="4" style="border-bottom: 1px solid #fff; text-align: left">(6б)</td>
			<td>
			<div class="softmerge-inner" style="width:38px;left:-1px"></div>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(6а)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:172px;left:-1px; font-size: 12px;">Грузоотправитель и его адрес</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;">--</td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(3)</td>
			<td colspan="2" style="font-size: 12px;">Валюта: наименование, код</td>
			<td colspan="6" style="border-bottom: 1px solid #000;">Российский рубль, 643</td>
			<td colspan="4" style="border-bottom: 1px solid #fff; text-align: left">(7)</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<!-- <td>(3)</td> -->
			<!-- <td> -->
			<!-- <div class="softmerge-inner" style="width:125px;left:-1px">ИНН/КПП покупателя</div> -->
			<!-- </td> -->
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(6б)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:172px;left:-1px; font-size: 12px;">Грузополучатель и его адрес</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;">--</td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(4)</td>
			<td colspan="2"><div class="softmerge-inner" style="width: 157px; left: -1px; font-size: 13px;">Идентификатор<br><span style="display: inline-block;width: 0;height: 0;line-height: 15px;white-space: pre;position: absolute;top: 1530px;font-size: 13px;
">государственного<br>контракта договора<br>(соглашения)</span></div></td>
			<!-- <td colspan="6" style="border-bottom: 1px solid #000;"></td> -->
			<!-- <td colspan="4" style="border-bottom: 1px solid #fff; text-align: left;">(8)</td> -->
			<td colspan="10"> <div style="border-bottom: 1px solid #000; width: 418px; margin-left: -5px; position: absolute; top: 1564px;"><span style="position: absolute; top: -16px; margin-left: 422px;">(8)</span></div> </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<!-- <td>(4)</td> -->
			<!-- <td> -->
			<!-- <div class="softmerge-inner" style="width:125px;left:-1px">Валюта: наименование, код</div> -->
			<!-- </td> -->
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<!-- <td> -->
			<!-- <div class="softmerge-inner" style="width:139px;left:-1px">Российский рубль, 643</div> -->
			<!-- </td> -->
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(7)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:194px;left:-1px; font-size: 12px;">К платежно-расчетному документу</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;">от</td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(5)</td>
			<td colspan="2">
			
			</td>
			<td colspan="10"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:140px;left:-1px">Документ об отгрузке</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;">
				<?php if($model->is_delivery_document): ?>
					№ п/п 1 № <?= $model->order ?> от <?= date('d.m.y', strtotime($model->date)) ?>
				<?php else: ?>
					№ п/п 1 № <?= $model->upd ?> от <?= date('d.m.y', strtotime($model->date_cr_prepayed)) ?>
				<?php endif; ?>
			</td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(5а)</td>



			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">&nbsp;</td>
			<td colspan="12">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" rowspan="2" style="border: 1px solid #000; border-right: 2px solid #000; border-left: 1px solid #000; text-align: center;">Код товара/ работ, услуг</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">N п/п</td>
			<td colspan="2" rowspan="2" style="border: 1px solid #000; width: 20pt; text-align: center;">Наименование товара (описание выполненных работ, оказанных услуг), имущественного права</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Код вида товара</td>
			<td colspan="2" style="border: 1px solid #000;">Единица измерения</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Коли-<br />
			чество (объем)</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Цена (тариф) за единицу измерения</td>
			<td colspan="3" rowspan="2" style="border: 1px solid #000; text-align: center;">Стоимость товаров<br />
			(работ, услуг), имущественных прав без налога - всего</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">В том числе сумма акциза</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Нало-<br />
			говая ставка</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Сумма налога, предъявляемая покупателю</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Стоимость товаров<br />
			(работ, услуг), имущественных прав с налогом - всего</td>
			<td colspan="4" style="border-bottom: 1px solid #000 !important; border-top: 1px solid #000 !important;">Страна происхождения товара</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Регистрационный номер декларации на товары или регистрационный номер партии товара, подлежащего прослеживаемости</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="1" style="border: 1px solid #000;text-align: center;">код</td>
			<td colspan="1" style="border: 1px solid #000;text-align: center;">условное обозначение<br>(национальное)</td>
			<td colspan="2" style="text-align: center; border-right: 1px solid #000 !important;">Цифро-<br />
			вой код</td>
			<td colspan="2" style="text-align: center;">Краткое наименование</td>

		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border: 1px solid #000; text-align: center; border-right: 2px solid #000; border-left: 1px solid #000;">А</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">Б</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">1</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">1а</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">2</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">2а</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">3</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">4</td>
			<td colspan="3" style="border: 1px solid #000; text-align: center;">5</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">6</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">7</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">8</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">9</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">10</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">10а</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">11</td>

		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border: 1px solid #000; text-align: center; border-right: 2px solid #000; border-left: 1px solid #000;">00000000038</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">1</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">

			<div style="height: 170px;">
			
			<?php if($model->bill_type_prepayed == 'Предоплата'): ?>
				Предоплата за транспортные услуги по заявке №<?= $model->order ?> от <?= ($model->date ? Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') : null) ?> Маршруту: <?= $model->rout ?>, Автомобиль <?=ArrayHelper::getValue($model, 'auto')?>, Водитель <?=ArrayHelper::getValue($model, 'driver.data')?>
                        <?php elseif($model->fio == 'Стандарт' || $model->fio == 'Международные'): ?>
				Транспортные услуги по Договору-Заявке №<?= $model->order ?> от <?= \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') ?>, маршрут <?= $model->rout ?>, Авто <?=ArrayHelper::getValue($model, 'autoString')?>, Водитель <?=ArrayHelper::getValue($model, 'driver.data')?>
			<?php elseif($model->fio == 'Срыв погрузки'): ?>
				Срыв погрузки по заявке №<?= $model->order ?> от <?= ($model->date ? Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') : null) ?> Маршруту: <?= $model->rout ?>, Автомобиль <?=ArrayHelper::getValue($model, 'autoString')?>, Водитель <?=ArrayHelper::getValue($model, 'driver.data')?>
			<?php elseif($model->fio == 'Ваш текст'): ?>
				<?= $model->your_text ?>
			<?php endif; ?>

				</div>
			</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">-</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">-</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">Рейс</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?= $model->flights_count ? $model->flights_count : 1 ?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?php
			// echo ArrayHelper::getValue($model, 'organization.nds') ? $I27 : $I29;
			echo '-';
			?></td>
			<td colspan="3" style="border: 1px solid #000; text-align: center;"><?= (ArrayHelper::getValue($model, 'organization.nds') && $model->fio != 'Международные') || true  ? number_format($I27, 2, ",", "") : number_format($I29, 2, ",", "") ?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">без акциза</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != 'Срыв погрузки'){
					if($model->fio != 'Международные' || true){
						echo '20%';
					} else {
						echo "0%";
					}
				} else {
					echo 'Без НДС';
				}

			?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != 'Срыв погрузки'){
					if($model->fio != 'Международные' || true){
						echo number_format($I28, 2, ",", "");
					} else {
						echo "—";
					}
				} else {
					echo '-';
				}

			?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?= number_format($I29, 2, ",", "") ?></td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">-</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">-</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">-</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border: 1px solid #000; border-right: 2px solid #000; border-left: 1px solid #000;">&nbsp;</td>
			<!-- <td colspan="6" style="border: 1px solid #000; border-right: 2px solid #000;">&nbsp;</td> -->
			<td colspan="11" style="border: 1px solid #000;">Всего к оплате</td>
			<!-- <td colspan="3" style="border: 1px solid #000;">&nbsp;</td> -->
			<td colspan="2" style="border: 1px solid #000; text-align: center;">X</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != 'Срыв погрузки'){
					if($model->fio != 'Международные' || true){
						echo number_format($I28, 2, ",", "");
					} else {
						echo "—";
					}
				} else {
					echo '-';
				}

			?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?= number_format($I29, 2, ",", "") ?></td>
			<td colspan="1" style="border: 1px solid #fff;"></td>
			<td colspan="1" style="border: 1px solid #fff;"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border-right: 2px solid #000;">Документ составлен на</td>
			<td>&nbsp;</td>
			<td colspan="12">Руководитель организации<br />
			или иное уполномоченное лицо</td>
			<td colspan="4">Главный бухгалтер<br />
			или иное уполномоченное лицо</td>
<!-- 			<td colspan="3" style="border-top: 1px solid #000;">(Подпись)</td>
			<td colspan="1"></td>
			<td colspan="2" style="border-top: 1px solid #000;">(ф.и.о)</td> -->
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">1</td>
			<td colspan="1" style="border-right: 2px solid #000;">листах</td>
			<td colspan="4"></td>
			<td colspan="3" style="border-top: 1px solid #000; text-align: center;">(подпись)</td>
			<td colspan="1"></td>
			<td colspan="4" style="border-top: 1px solid #000; text-align: center; position: relative;">(ф.и.о.)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?php
				if(stripos(ArrayHelper::getValue($model, 'organization.name'), "ООО") !== false) {
					echo ArrayHelper::getValue($model, 'organization.initials');
				}
			 ?></span></td>
			<td colspan="3">&nbsp;</td>
			<td colspan="3" style="border-top: 1px solid #000;">(подпись)</td>
			<td>&nbsp;</td>
			<td colspan="1" style="border-top: 1px solid #000; text-align: center; position: relative;">(ф.и.о.)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap; font-size: 12px;"><?php
			if(stripos(ArrayHelper::getValue($model, 'organization.name'), "ООО") !== false){
				echo ArrayHelper::getValue($model, 'organization.initials');
			}

			 ?></span></td>
			<td colspan="3">&nbsp;</td></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border-right: 2px solid #000;">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="19">Индивидуальный предприниматель</td>
			<!-- <td colspan="6">&nbsp;</td> -->
			<!-- <td>&nbsp;</td> -->
			<!-- <td colspan="15">&nbsp;</td> -->
			<!-- <td colspan="3">&nbsp;</td> -->
			<!-- <td colspan="35">&nbsp;</td> -->
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border-right: 2px solid #000;">&nbsp;</td>
			<td style="border-bottom: 2px solid #000;">&nbsp;</td>
			<td colspan="3" style="border-bottom: 2px solid #000;">или иное уполномоченное лицо</td>
			<td colspan="3"  style="border-bottom: 2px solid #000; border-top: 1px solid #000; text-align: center;">(подпись)</td>
			<td  style="border-bottom: 2px solid #000;"><span style="display: block; position: absolute; top: 684px; left: 800px;"><?php
				if(stripos(ArrayHelper::getValue($model, 'organization.name'), "ИП") !== false) {
					echo ArrayHelper::getValue($model, 'organization.initials');
				}

			?></span></td>
			<td colspan="4"  style="border-bottom: 2px solid #000; border-top: 1px solid #000; text-align: center;">(ф.и.о.)</td>
			<td colspan="1" style="border-bottom: 2px solid #000;"></td>
			<td colspan="8" style="border-bottom: 2px solid #000; border-top: 1px solid #000; text-align: center; font-size: 13px;">(реквизиты свидетельства о государственной регистрации индивидуального предпринимателя)</td>
		</tr>
	</tbody>
</table>

<table style="display: none;">
<thead></thead>
<tbody>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">Основание передачи (сдачи) / получения (приемки)</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="5">&nbsp;</td>
			<td colspan="3">[8]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">&nbsp;</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="5" style="text-align: center; border-top: 1px solid #000;">(договор; доверенность и др.)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="13">Данные о транспортировке и грузе</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="2">[9]</td>
		</tr>
		<tr>
			<td colspan="8">&nbsp;</td>
			<td colspan="9" style="text-align: center; border-top: 1px solid #000; font-size: 13px; white-space: nowrap;">(транспортная накладная, поручение экспедитору, экспедиторская / складская расписка и др. / масса нетто/ брутто груза, если не приведены ссылки на транспортные документы, содержащие эти сведения)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="14">Товар (груз) передал / услуги, результаты работ, права сдал</td>
			<td>&nbsp;</td>
			<td colspan="14">Товар (груз) получил / услуги, результаты работ, права принял</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1"></td>
			<td colspan="3">[10]</td>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="3">[15]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2" style="text-align: center; border-top: 1px solid #000;">(должность)</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="3" style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="3" style="text-align: center: border-top: 1px solid #000;">(ф.и.о.)</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="13">(должность)</td>
			<td>&nbsp;</td>
			<td colspan="10">(подпись)</td>
			<td>&nbsp;</td>
			<td colspan="14">(ф.и.о.)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="15">Дата отгрузки, передачи (сдачи)</td>
			<td>&quot;</td>
			<td colspan="2">#ОШИБКА!</td>
			<td>&quot;</td>
			<td colspan="9">#ОШИБКА!</td>
			<td colspan="2">20</td>
			<td colspan="2">#ОШИБКА!</td>
			<td colspan="10">г.</td>
			<td colspan="3">[11]</td>
			<td>&nbsp;</td>
			<td colspan="13">Дата получения (приемки)</td>
			<td>&quot;</td>
			<td colspan="2">&nbsp;</td>
			<td>&quot;</td>
			<td colspan="6">&nbsp;</td>
			<td colspan="2">20</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="12">г.</td>
			<td colspan="3">[16]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">Иные сведения об отгрузке, передаче</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">Иные сведения о получении, приемке</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">&nbsp;</td>
			<td colspan="3">[12]</td>
			<td>&nbsp;</td>
			<td colspan="39">Претензий нет</td>
			<td colspan="3">[17]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">(ссылки на неотъемлемые приложения, сопутствующие документы, иные документы и т.п.)</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">(информация о наличии/отсутствии претензии; ссылки на неотъемлемые приложения, и другие документы и т.п.)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">Ответственный за правильность оформления факта хозяйственной жизни</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">Ответственный за правильность оформления факта хозяйственной жизни</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="14">#ОШИБКА!</td>
			<td colspan="3">[13]</td>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="10">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="14">&nbsp;</td>
			<td colspan="3">[18]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="13">(должность)</td>
			<td>&nbsp;</td>
			<td colspan="13">(подпись)</td>
			<td>&nbsp;</td>
			<td colspan="14">(ф.и.о.)</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="13">(должность)</td>
			<td>&nbsp;</td>
			<td colspan="10">(подпись)</td>
			<td>&nbsp;</td>
			<td colspan="14">(ф.и.о.)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">Наименование экономического субъекта &ndash; составителя документа (в т.ч. комиссионера / агента)</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">Наименование экономического субъекта - составителя документа</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="3">[14]</td>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="16">#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="3">[19]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">(М.П может не заполняться при проставлении печати, может быть указан ИНН / КПП)</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">(М.П может не заполняться при проставлении печати, может быть указан ИНН / КПП)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="13">М.П.</td>
			<td colspan="29">&nbsp;</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="13">М.П.</td>
			<td colspan="26">&nbsp;</td>
			<td colspan="3">&nbsp;</td>
		</tr>
	</tbody>
</table>

<table style="margin-bottom: 0;">
	<thead>
		
	</thead>
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">Основание передачи (сдачи) / получения (приемки)</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="5">&nbsp;</td>
			<td colspan="3"><span style="position: absolute; right: 20px;">[8]</span></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3">&nbsp;</td>
			<td colspan="5">&nbsp;</td>
			<td colspan="2" style="text-align: center; border-top: 1px solid #000;"><span style="display: inline-block; position: absolute; bottom: 507; left: 450;">Договор-заявка №<?= $model->order ?> от <?= \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') ?> г.</span>(договор; доверенность и др.)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="5">Данные о транспортировке и грузе</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="2"><span style="position: absolute; right: 20px;">[9]</span></td>
		</tr>
		<tr>
			<td colspan="8">&nbsp;</td>
			<td colspan="2" style="text-align: center; border-top: 1px solid #000; font-size: 12px; white-space: nowrap;">(транспортная накладная, поручение экспедитору, экспедиторская / складская расписка и др. / масса нетто/ брутто груза, если не приведены ссылки на транспортные документы, содержащие эти сведения)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>

<div class="container" style="padding: 0 20px;">
	<div style="display: inline-block; width: 48%; border-right: 2px solid #000;">
		<p>Товар (груз) передал / услуги, результаты работ, права сдал</p>
		<table style="margin-top: 25px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000; position: relative;">(должность)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'organization.doljnost_rukovoditelya') ?></span></td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000; position: relative;">(ф.и.о)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'organization.initials') ?></span></td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; left: 820px;">[10]</span></td>
				</tr>
			</tbody>
		</table>
		<table style="margin-top: 1px;">
			<thead></thead>
			<tbody>
				<tr>
					<td>Дата отгрузки, передачи (сдачи)<br>Иные сведения об отгрузке, передаче</td>
					<td style="text-align: center; position: relative;">_____<span style="display: block; position: absolute; bottom: 15px; left: 33px; white-space: nowrap;"><?= date('d', strtotime($model->date_cr)) ?></span></td>
					<td style="text-align: center; position: relative;">_____________<span style="display: block; position: absolute; bottom: 15px; left: 45px; white-space: nowrap;"><?php $month = date('n', strtotime($model->date_cr))-1; ?><?= $rusMonth[$month] ?></span></td>
					<td style="text-align: center; text-decoration: underline;"><?= date('Y', strtotime($model->date_cr)) ?>г.</td>
					<td style="text-align: center; vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="position: absolute; left: 820px;">[11]</span></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
				</tr>
			</tbody>
		</table>
		<table style="margin-top: 10px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000;">(ссылки на неотъемлемые приложения, сопутствующие документы, иные документы и т.п.)</td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1" style="text-align: center; vertical-align: middle;"><span style="position: absolute; left: 820px;">[12]</span></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
				</tr>
			</tbody>
		</table>
		<p>Ответственный за правильность оформления факта хозяйственной жизни</p>
		<table style="margin-top: 15px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000;">(должность)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(ф.и.о)<span style="display: block; position: absolute; top: 1082px; left: 640px; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'organization.initials'); ?></span></td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; left: 820px;">[13]</span></td>
				</tr>
			</tbody>
		</table>
		<p>Наименование экономического субъекта – составителя документа (в т.ч. комиссионера / агента)</p>
		<table style="margin-top: 25px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000; position: relative;">(может не заполняться при проставлении печати в М.П., может быть указан ИНН / КПП)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'organization.name') ?></span></td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; left: 820px;">[14]</span></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="display: inline-block; width: 48%;">
		<p>Товар (груз) передал / услуги, результаты работ, права принял</p>
		<table style="margin-top: 15px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000;">(должность)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(ф.и.о)</td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; right: 20px;">[15]</span></td>
				</tr>
			</tbody>
		</table>
		<table style="margin-top: 1px;">
			<thead></thead>
			<tbody>
				<tr>
					<td>Дата получения (приемки)<br>Иные сведения о получении, приемке</td>
					<td style="text-align: center;">_____</td>
					<td style="text-align: center;">_____________</td>
					<td style="text-align: center;">20___г.</td>
					<td style="text-align: center; vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="position: absolute; right: 20px;">[16]</span></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
				</tr>
			</tbody>
		</table>
		<table style="margin-top: 10px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000; font-size: 11px;">(информация о наличии/отсутствии претензии; ссылки на неотъемлемые приложения, и другие  документы и т.п.)</td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1" style="text-align: center; vertical-align: middle;"><span style="position: absolute; right: 20px;">[17]</span></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
				</tr>
			</tbody>
		</table>
		<p>Ответственный за правильность оформления факта хозяйственной жизни</p>
		<table style="margin-top: 15px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000;">(должность)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(ф.и.о)</td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; right: 20px;">[18]</span></td>
				</tr>
			</tbody>
		</table>
		<p>Наименование экономического субъекта - составителя документа</p>
		<table style="margin-top: 25px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000; position: relative;">(может не заполняться при проставлении печати в М.П., может быть указан ИНН / КПП)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'zakazchik.name') ?></span></td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; right: 20px;">[19]</span></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
	
</div>


<div class="pg-break"></div>



<div style="margin-left: 15px;">


<table border="0" cellpadding="0" cellspacing="0" class="no-grid waffle">
	<thead>
	</thead>
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" rowspan="4" style="border-right: 2px solid #000; font-size: 25px; line-height: 30px;">Авансовый УПД</td>
			<td colspan="2">Счет-фактура N</td>
			<td colspan="2" style="text-align: center; border-bottom: 1px solid #000;"><?= $model->number_prepayed ?></td>
			<td colspan="2" style="text-align: center;">от</td>
			<td colspan="2" style="white-space: nowrap; text-align: center; border-bottom: 1px solid #000;"><?php $month = date('n', strtotime($model->date_cr_prepayed))-1; ?><?= date('d '.$rusMonth[$month].' Y г.', strtotime($model->date_cr)) ?></td>
			<td colspan="4">(1)</td>
			<td colspan="10" rowspan="2" style="font-size: 11px; text-align: right;">Приложение № 1 к постановлению Правительства Российской Федерации от 26 декабря 2011 г. № 1137<br />
			(в редакции постановления Правительства Российской Федерации от 2 апреля 2021 г. № 534)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Исправление N</td>
			<td colspan="2" style="text-align: center; border-bottom: 1px solid #000;"></td>
			<td colspan="2" style="text-align: center;">от</td>
			<td colspan="2" style="white-space: nowrap; text-align: center; border-bottom: 1px solid #000;">&nbsp;</td>
			<td colspan="4">(1а)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="35">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:96px;left:-1px">Продавец</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'organization.name') ?></td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(2)</td>
			<td colspan="2">Покупатель</td>
			<td colspan="6" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'zakazchik.name'); ?></td>
			<td colspan="4" style="border-bottom: 1px solid #fff; text-align: left">(6)</td>
			<td>
			<div class="softmerge-inner" style="width:82px;left:-1px"></div>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(6)</td>
		</tr>
		<tr>
		
			<td>&nbsp;</td>
			<td colspan="1">Статус:</td>
			<td colspan="1" style="text-align: center; border: 2px solid #000;"><?= ArrayHelper::getValue($model, 'organization.nds') ? '1' : '2' ?></td>
			<td style="border-right: 2px solid #000;">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1">
			<div class="softmerge-inner" style="width:42px;left:-1px">Адрес</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'organization.official_address') ?></td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(2a)</td>
			<td colspan="2">Адрес</td>
			<td colspan="6" style="border-bottom: 1px solid #000; font-size: 12px; height: 39px;"><?= ArrayHelper::getValue($model, 'zakazchik.official_address') ?></td>
			<td colspan="4" style="border-bottom: 1px solid #fff; text-align: left">(6а)</td>
			<td>
			<div class="softmerge-inner" style="width:17px;left:-1px"></div>
			</td>
			<td>
			<div class="softmerge-inner" style="width:49px;left:-1px"></div>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" rowspan="6" style="border-right: 2px solid #000; vertical-align: initial;"><br />
			1 - счет-фактура и передаточный документ (акт)<br />
			2 - передаточный документ (акт)</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:143px;left:-1px; font-size: 13px;">ИНН/КПП продавца</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'organization.inn') ?> / <?= ArrayHelper::getValue($model, 'organization.kpp') ?></td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(2б)</td>
			<td colspan="2">ИНН/КПП покупателя</td>
			<td colspan="6" style="border-bottom: 1px solid #000;"><?= ArrayHelper::getValue($model, 'zakazchik.inn') ?> / <?= ArrayHelper::getValue($model, 'zakazchik.kpp') ?></td>
			<td colspan="4" style="border-bottom: 1px solid #fff; text-align: left">(6б)</td>
			<td>
			<div class="softmerge-inner" style="width:38px;left:-1px"></div>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(6а)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:172px;left:-1px; font-size: 12px;">Грузоотправитель и его адрес</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;">--</td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(3)</td>
			<td colspan="2" style="font-size: 12px;">Валюта: наименование, код</td>
			<td colspan="6" style="border-bottom: 1px solid #000;">Российский рубль, 643</td>
			<td colspan="4" style="border-bottom: 1px solid #fff; text-align: left">(7)</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<!-- <td>(3)</td> -->
			<!-- <td> -->
			<!-- <div class="softmerge-inner" style="width:125px;left:-1px">ИНН/КПП покупателя</div> -->
			<!-- </td> -->
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(6б)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:172px;left:-1px; font-size: 12px;">Грузополучатель и его адрес</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;">--</td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(4)</td>
			<td colspan="2"><div class="softmerge-inner" style="width: 157px; left: -1px; font-size: 13px;">Идентификатор<br><span style="display: inline-block;width: 0;height: 0;line-height: 15px;white-space: pre;position: absolute;top: 240px;font-size: 13px;
">государственного<br>контракта договора<br>(соглашения)</span></div></td>
			<!-- <td colspan="6" style="border-bottom: 1px solid #000;"></td> -->
			<!-- <td colspan="4" style="border-bottom: 1px solid #fff; text-align: left;">(8)</td> -->
			<td colspan="10"> <div style="border-bottom: 1px solid #000; width: 418px; margin-left: -5px; position: absolute; top: 274px;"><span style="position: absolute; top: -16px; margin-left: 422px;">(8)</span></div> </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<!-- <td>(4)</td> -->
			<!-- <td> -->
			<!-- <div class="softmerge-inner" style="width:125px;left:-1px">Валюта: наименование, код</div> -->
			<!-- </td> -->
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<!-- <td> -->
			<!-- <div class="softmerge-inner" style="width:139px;left:-1px">Российский рубль, 643</div> -->
			<!-- </td> -->
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>(7)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:194px;left:-1px; font-size: 12px;">К платежно-расчетному документу</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;">от</td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(5)</td>
			<td colspan="2">
			
			</td>
			<td colspan="10"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
			<div class="softmerge-inner" style="width:140px;left:-1px">Документ об отгрузке</div>
			</td>
			<td colspan="9" style="border-bottom: 1px solid #000;">
			<?php if($model->is_delivery_document): ?>
					№ п/п 1 № <?= $model->order ?> от <?= date('d.m.y', strtotime($model->date)) ?>
				<?php else: ?>
					№ п/п 1 № <?= $model->upd ?> от <?= date('d.m.y', strtotime($model->date_cr)) ?>
				<?php endif; ?>
			</td>
			<td colspan="1" style="border-bottom: 1px solid #fff; text-align: center">(5а)</td>


			

			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td style="border-bottom: 1px solid #fff;">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">&nbsp;</td>
			<td colspan="12">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" rowspan="2" style="border: 1px solid #000; border-right: 2px solid #000; border-left: 1px solid #000; text-align: center;">Код товара/ работ, услуг</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">N п/п</td>
			<td colspan="2" rowspan="2" style="border: 1px solid #000; width: 20pt; text-align: center;">Наименование товара (описание выполненных работ, оказанных услуг), имущественного права</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Код вида товара</td>
			<td colspan="2" style="border: 1px solid #000;">Единица измерения</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Коли-<br />
			чество (объем)</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Цена (тариф) за единицу измерения</td>
			<td colspan="3" rowspan="2" style="border: 1px solid #000; text-align: center;">Стоимость товаров<br />
			(работ, услуг), имущественных прав без налога - всего</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">В том числе сумма акциза</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Нало-<br />
			говая ставка</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Сумма налога, предъявляемая покупателю</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Стоимость товаров<br />
			(работ, услуг), имущественных прав с налогом - всего</td>
			<td colspan="4" style="border-bottom: 1px solid #000 !important; border-top: 1px solid #000 !important;">Страна происхождения товара</td>
			<td colspan="1" rowspan="2" style="border: 1px solid #000; text-align: center;">Регистрационный номер декларации на товары или регистрационный номер партии товара, подлежащего прослеживаемости</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="1" style="border: 1px solid #000;text-align: center;">код</td>
			<td colspan="1" style="border: 1px solid #000;text-align: center;">условное обозначение<br>(национальное)</td>
			<td colspan="2" style="text-align: center; border-right: 1px solid #000 !important;">Цифро-<br />
			вой код</td>
			<td colspan="2" style="text-align: center;">Краткое наименование</td>

		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border: 1px solid #000; text-align: center; border-right: 2px solid #000; border-left: 1px solid #000;">А</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">Б</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">1</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">1а</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">2</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">2а</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">3</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">4</td>
			<td colspan="3" style="border: 1px solid #000; text-align: center;">5</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">6</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">7</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">8</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">9</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">10</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">10а</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">11</td>

		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border: 1px solid #000; text-align: center; border-right: 2px solid #000; border-left: 1px solid #000;">00000000038</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">1</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">

			<div style="height: 170px;">
			<?php if($model->bill_type_prepayed == 'Предоплата'): ?>
				Предоплата за транспортные услуги по заявке №<?= $model->order ?> от <?= ($model->date ? Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') : null) ?> Маршруту: <?= $model->rout ?>, Автомобиль <?=ArrayHelper::getValue($model, 'auto')?>, Водитель <?=ArrayHelper::getValue($model, 'driver.data')?>
			<?php elseif($model->fio == 'Стандарт' || $model->fio == 'Международные'): ?>
				Транспортные услуги по Договору-Заявке №<?= $model->order ?> от <?= \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') ?>, маршрут <?= $model->rout ?>, Авто <?=ArrayHelper::getValue($model, 'autoString')?>, Водитель <?=ArrayHelper::getValue($model, 'driver.data')?>
			<?php elseif($model->fio == 'Срыв погрузки'): ?>
				Срыв погрузки по заявке №<?= $model->order ?> от <?= ($model->date ? Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') : null) ?> Маршруту: <?= $model->rout ?>, Автомобиль <?=ArrayHelper::getValue($model, 'autoString')?>, Водитель <?=ArrayHelper::getValue($model, 'driver.data')?>
			<?php elseif($model->fio == 'Предоплата'): ?>
				Предоплата за транспортные услуги по заявке №<?= $model->order ?> от <?= ($model->date ? Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') : null) ?> Маршруту: <?= $model->rout ?>, Автомобиль <?=ArrayHelper::getValue($model, 'autoString')?>, Водитель <?=ArrayHelper::getValue($model, 'driver.data')?>
			<?php elseif($model->fio == 'Ваш текст'): ?>
				<?= $model->your_text ?>
			<?php endif; ?>
				</div>
			</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">-</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">-</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">Рейс</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?= $model->flights_count ? $model->flights_count : 1 ?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?php
			// echo ArrayHelper::getValue($model, 'organization.nds') ? $I27 : $I29;
			echo '-';
			?></td>
			<td colspan="3" style="border: 1px solid #000; text-align: center;"><?= ArrayHelper::getValue($model, 'organization.nds') ? number_format($I27, 2, ",", "") : number_format($I29, 2, ",", "") ?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">без акциза</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != 'Срыв погрузки'){
					if($model->fio != 'Международные' || true){
						echo '20%';						
					} else {
						echo "0%";
					}
				} else {
					echo 'Без НДС';
				}

			?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != 'Срыв погрузки'){
					if($model->fio != 'Международные' || true){
						echo number_format($I28, 2, ",", "");
					} else {
						echo "—";
					}
				} else {
					echo '-';
				}

			?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?= number_format($I29, 2, ",", "") ?></td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">-</td>
			<td colspan="2" style="border: 1px solid #000; text-align: center;">-</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;">-</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border: 1px solid #000; border-right: 2px solid #000; border-left: 1px solid #000;">&nbsp;</td>
			<!-- <td colspan="6" style="border: 1px solid #000; border-right: 2px solid #000;">&nbsp;</td> -->
			<td colspan="11" style="border: 1px solid #000;">Всего к оплате</td>
			<!-- <td colspan="3" style="border: 1px solid #000;">&nbsp;</td> -->
			<td colspan="2" style="border: 1px solid #000; text-align: center;">X</td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != 'Срыв погрузки'){
					if($model->fio != 'Международные' || true){
						echo number_format($I28, 2, ",", "");
					} else {
						echo "—";
					}
				} else {
					echo '-';
				}


			?></td>
			<td colspan="1" style="border: 1px solid #000; text-align: center;"><?= number_format($I29, 2, ",", "") ?></td>
			<td colspan="1" style="border: 1px solid #fff;"></td>
			<td colspan="1" style="border: 1px solid #fff;"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border-right: 2px solid #000;">Документ составлен на</td>
			<td>&nbsp;</td>
			<td colspan="12">Руководитель организации<br />
			или иное уполномоченное лицо</td>
			<td colspan="4">Главный бухгалтер<br />
			или иное уполномоченное лицо</td>
<!-- 			<td colspan="3" style="border-top: 1px solid #000;">(Подпись)</td>
			<td colspan="1"></td>
			<td colspan="2" style="border-top: 1px solid #000;">(ф.и.о)</td> -->
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">1</td>
			<td colspan="1" style="border-right: 2px solid #000;">листах</td>
			<td colspan="4"></td>
			<td colspan="3" style="border-top: 1px solid #000; text-align: center;">(подпись)</td>
			<td colspan="1"></td>
			<td colspan="4" style="border-top: 1px solid #000; text-align: center; position: relative;">(ф.и.о.)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?php
				if(stripos(ArrayHelper::getValue($model, 'organization.name'), "ООО") !== false) {
					echo ArrayHelper::getValue($model, 'organization.initials');
				}
			 ?></span></td>
			<td colspan="3">&nbsp;</td>
			<td colspan="3" style="border-top: 1px solid #000;">(подпись)</td>
			<td>&nbsp;</td>
			<td colspan="1" style="border-top: 1px solid #000; text-align: center; position: relative;">(ф.и.о.)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap; font-size: 12px;"><?php
			if(stripos(ArrayHelper::getValue($model, 'organization.name'), "ООО") !== false){
				echo ArrayHelper::getValue($model, 'organization.initials');
			}

			 ?></span></td>
			<td colspan="3">&nbsp;</td></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border-right: 2px solid #000;">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="19">Индивидуальный предприниматель</td>
			<!-- <td colspan="6">&nbsp;</td> -->
			<!-- <td>&nbsp;</td> -->
			<!-- <td colspan="15">&nbsp;</td> -->
			<!-- <td colspan="3">&nbsp;</td> -->
			<!-- <td colspan="35">&nbsp;</td> -->
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" style="border-right: 2px solid #000;">&nbsp;</td>
			<td style="border-bottom: 2px solid #000;">&nbsp;</td>
			<td colspan="3" style="border-bottom: 2px solid #000;">или иное уполномоченное лицо</td>
			<td colspan="3"  style="border-bottom: 2px solid #000; border-top: 1px solid #000; text-align: center;">(подпись)</td>
			<td  style="border-bottom: 2px solid #000;"><span style="display: block; position: absolute; top: 1995px; left: 800px;"><?php
				if(stripos(ArrayHelper::getValue($model, 'organization.name'), "ИП") !== false) {
					echo ArrayHelper::getValue($model, 'organization.initials');
				}

			?></span></td>
			<td colspan="4"  style="border-bottom: 2px solid #000; border-top: 1px solid #000; text-align: center;">(ф.и.о.)</td>
			<td colspan="1" style="border-bottom: 2px solid #000;"></td>
			<td colspan="8" style="border-bottom: 2px solid #000; border-top: 1px solid #000; text-align: center; font-size: 13px;">(реквизиты свидетельства о государственной регистрации индивидуального предпринимателя)</td>
		</tr>
	</tbody>
</table>

<table style="display: none;">
<thead></thead>
<tbody>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">Основание передачи (сдачи) / получения (приемки)</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="5">&nbsp;</td>
			<td colspan="3">[8]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">&nbsp;</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="5" style="text-align: center; border-top: 1px solid #000;">(договор; доверенность и др.)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="13">Данные о транспортировке и грузе</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="2">[9]</td>
		</tr>
		<tr>
			<td colspan="8">&nbsp;</td>
			<td colspan="9" style="text-align: center; border-top: 1px solid #000; font-size: 13px; white-space: nowrap;">(транспортная накладная, поручение экспедитору, экспедиторская / складская расписка и др. / масса нетто/ брутто груза, если не приведены ссылки на транспортные документы, содержащие эти сведения)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="14">Товар (груз) передал / услуги, результаты работ, права сдал</td>
			<td>&nbsp;</td>
			<td colspan="14">Товар (груз) получил / услуги, результаты работ, права принял</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1"></td>
			<td colspan="3">[10]</td>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="3">[15]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2" style="text-align: center; border-top: 1px solid #000;">(должность)</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="3" style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="3" style="text-align: center: border-top: 1px solid #000;">(ф.и.о.)</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="1">&nbsp;</td>
			<td colspan="13">(должность)</td>
			<td>&nbsp;</td>
			<td colspan="10">(подпись)</td>
			<td>&nbsp;</td>
			<td colspan="14">(ф.и.о.)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="15">Дата отгрузки, передачи (сдачи)</td>
			<td>&quot;</td>
			<td colspan="2">#ОШИБКА!</td>
			<td>&quot;</td>
			<td colspan="9">#ОШИБКА!</td>
			<td colspan="2">20</td>
			<td colspan="2">#ОШИБКА!</td>
			<td colspan="10">г.</td>
			<td colspan="3">[11]</td>
			<td>&nbsp;</td>
			<td colspan="13">Дата получения (приемки)</td>
			<td>&quot;</td>
			<td colspan="2">&nbsp;</td>
			<td>&quot;</td>
			<td colspan="6">&nbsp;</td>
			<td colspan="2">20</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="12">г.</td>
			<td colspan="3">[16]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">Иные сведения об отгрузке, передаче</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">Иные сведения о получении, приемке</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">&nbsp;</td>
			<td colspan="3">[12]</td>
			<td>&nbsp;</td>
			<td colspan="39">Претензий нет</td>
			<td colspan="3">[17]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">(ссылки на неотъемлемые приложения, сопутствующие документы, иные документы и т.п.)</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">(информация о наличии/отсутствии претензии; ссылки на неотъемлемые приложения, и другие документы и т.п.)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">Ответственный за правильность оформления факта хозяйственной жизни</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">Ответственный за правильность оформления факта хозяйственной жизни</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="14">#ОШИБКА!</td>
			<td colspan="3">[13]</td>
			<td>&nbsp;</td>
			<td colspan="13">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="10">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="14">&nbsp;</td>
			<td colspan="3">[18]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="13">(должность)</td>
			<td>&nbsp;</td>
			<td colspan="13">(подпись)</td>
			<td>&nbsp;</td>
			<td colspan="14">(ф.и.о.)</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="13">(должность)</td>
			<td>&nbsp;</td>
			<td colspan="10">(подпись)</td>
			<td>&nbsp;</td>
			<td colspan="14">(ф.и.о.)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">Наименование экономического субъекта &ndash; составителя документа (в т.ч. комиссионера / агента)</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">Наименование экономического субъекта - составителя документа</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="3">[14]</td>
			<td>&nbsp;</td>
			<td>#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="16">#ОШИБКА!</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="3">[19]</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="42">(М.П может не заполняться при проставлении печати, может быть указан ИНН / КПП)</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="39">(М.П может не заполняться при проставлении печати, может быть указан ИНН / КПП)</td>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="13">М.П.</td>
			<td colspan="29">&nbsp;</td>
			<td colspan="3">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="13">М.П.</td>
			<td colspan="26">&nbsp;</td>
			<td colspan="3">&nbsp;</td>
		</tr>
	</tbody>
</table>

<table style="margin-bottom: 0;">
	<thead>
		
	</thead>
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td colspan="8">Основание передачи (сдачи) / получения (приемки)</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="5">&nbsp;</td>
			<td colspan="3"><span style="position: absolute; right: 20px;">[8]</span></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3">&nbsp;</td>
			<td colspan="5">&nbsp;</td>
			<td colspan="2" style="text-align: center; border-top: 1px solid #000;"><span style="display: inline-block; position: absolute; bottom: -806; left: 450;"> Договор-заявка №<?= $model->order ?> от <?= \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y') ?> г.</span>(договор; доверенность и др.)</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="5">Данные о транспортировке и грузе</td>
			<td colspan="2">&nbsp;</td>
			<td colspan="2"><span style="position: absolute; right: 20px;">[9]</span></td>
		</tr>
		<tr>
			<td colspan="8">&nbsp;</td>
			<td colspan="2" style="text-align: center; border-top: 1px solid #000; font-size: 12px; white-space: nowrap;">(транспортная накладная, поручение экспедитору, экспедиторская / складская расписка и др. / масса нетто/ брутто груза, если не приведены ссылки на транспортные документы, содержащие эти сведения)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>

<div class="container" style="padding: 0 20px;">
	<div style="display: inline-block; width: 48%; border-right: 2px solid #000;">
		<p>Товар (груз) передал / услуги, результаты работ, права сдал</p>
		<table style="margin-top: 25px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000; position: relative;">(должность)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'organization.doljnost_rukovoditelya') ?></span></td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000; position: relative;">(ф.и.о)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'organization.initials') ?></span></td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; left: 820px;">[10]</span></td>
				</tr>
			</tbody>
		</table>
		<table style="margin-top: 1px;">
			<thead></thead>
			<tbody>
				<tr>
					<td>Дата отгрузки, передачи (сдачи)<br>Иные сведения об отгрузке, передаче</td>
					<td style="text-align: center; position: relative;">_____<span style="display: block; position: absolute; bottom: 15px; left: 33px; white-space: nowrap;"><?= date('d', strtotime($model->date_cr)) ?></span></td>
					<td style="text-align: center; position: relative;">_____________<span style="display: block; position: absolute; bottom: 15px; left: 45px; white-space: nowrap;"><?php $month = date('n', strtotime($model->date_cr))-1; ?><?= $rusMonth[$month] ?></span></td>
					<td style="text-align: center; text-decoration: underline;"><?= date('Y', strtotime($model->date_cr)) ?>г.</td>
					<td style="text-align: center; vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="position: absolute; left: 820px;">[11]</span></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
				</tr>
			</tbody>
		</table>
		<table style="margin-top: 10px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000;">(ссылки на неотъемлемые приложения, сопутствующие документы, иные документы и т.п.)</td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1" style="text-align: center; vertical-align: middle;"><span style="position: absolute; left: 820px;">[12]</span></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
				</tr>
			</tbody>
		</table>

		<p>Ответственный за правильность оформления факта хозяйственной жизни</p>
		<table style="margin-top: 15px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000;">(должность)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(ф.и.о)<span style="display: block; position: absolute; top: 2395px; left: 640px; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'organization.initials') ?></span></td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; left: 820px;">[13]</span></td>
				</tr>
			</tbody>
		</table>
		<p>Наименование экономического субъекта – составителя документа (в т.ч. комиссионера / агента)</p>
		<table style="margin-top: 25px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000; position: relative;">(может не заполняться при проставлении печати в М.П., может быть указан ИНН / КПП)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'organization.name') ?></span></td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; left: 820px;">[14]</span></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="display: inline-block; width: 48%;">
		<p>Товар (груз) передал / услуги, результаты работ, права принял</p>
		<table style="margin-top: 15px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000;">(должность)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(ф.и.о)</td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; right: 20px;">[15]</span></td>
				</tr>
			</tbody>
		</table>
		<table style="margin-top: 1px;">
			<thead></thead>
			<tbody>
				<tr>
					<td>Дата получения (приемки)<br>Иные сведения о получении, приемке</td>
					<td style="text-align: center;">_____</td>
					<td style="text-align: center;">_____________</td>
					<td style="text-align: center;">20___г.</td>
					<td style="text-align: center; vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="position: absolute; right: 20px;">[16]</span></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
				</tr>
			</tbody>
		</table>
		<table style="margin-top: 10px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000; font-size: 11px;">(информация о наличии/отсутствии претензии; ссылки на неотъемлемые приложения, и другие  документы и т.п.)</td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1" style="text-align: center; vertical-align: middle;"><span style="position: absolute; right: 20px;">[17]</span></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
					<td colspan="1"></td>
				</tr>
			</tbody>
		</table>
		<p>Ответственный за правильность оформления факта хозяйственной жизни</p>
		<table style="margin-top: 15px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000;">(должность)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(подпись)</td>
					<td></td>
					<td style="text-align: center; border-top: 1px solid #000;">(ф.и.о)</td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; right: 20px;">[18]</span></td>
				</tr>
			</tbody>
		</table>
		<p>Наименование экономического субъекта - составителя документа</p>
		<table style="margin-top: 25px;">
			<thead></thead>
			<tbody>
				<tr>
					<td style="text-align: center; border-top: 1px solid #000; position: relative;">(может не заполняться при проставлении печати в М.П., может быть указан ИНН / КПП)<span style="display: block; position: absolute; top: -18px; left: 0; white-space: nowrap;"><?= ArrayHelper::getValue($model, 'zakazchik.name') ?></span></td>
					<td style="text-align: center; vertical-align: middle;"><span style="position: absolute; right: 20px;">[19]</span></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
	
</div>

<?php if($model->is_signature && false): ?>
	<img src="/<?= ArrayHelper::getValue($model, 'organization.pechat') ?>" style="position: fixed; bottom: 25px; left: 25px; height: 100px">
<?php endif; ?>

<div class="pg-break"></div>




<!-- <div style="position: absolute; bottom: 2px; left: 50px;">М.П.</div> -->
<!-- <div style="position: absolute; bottom: 2px; right: 750px;">М.П.</div> -->