<?php

use yii\db\Migration;

/**
 * Class m240916_145546_insert_template_client_table
 */
class m240916_145546_insert_template_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->insert('template_client',array(
            'name' =>'Договор Заказчик (старая база)',
            'type'=>432,
            'text'=>'<p style="text-align:center">&nbsp;</p>

<p><span style="font-size:22px"><strong>Транспортная компания&nbsp;{organization.name}&nbsp; &nbsp;&nbsp;тел. 8-800-201-16-50&nbsp; сот.&nbsp;<span style="font-family:sans-serif,arial,verdana,trebuchet ms">{user.phone}</span></strong></span></p>

<p>&nbsp;</p>

<p><span style="font-size:22px"><strong>Юридический адрес</strong></span>&nbsp;<span style="font-size:22px"><strong>{organization.official_address}&nbsp; &nbsp;&nbsp;</strong></span></p>

<p>&nbsp;</p>

<p><span style="font-size:22px"><strong>Почтовый адрес {user.post_address}</strong></span>&nbsp; &nbsp;</p>

<p>&nbsp;</p>

<hr />
<p>&nbsp;</p>

<p>&nbsp;</p>

<p style="text-align:center"><span style="font-size:16px"><strong>ДОГОВОР-ЗАЯВКА № {order}&nbsp;НА ОКАЗАНИЕ ТРАНСПОРТНО-ЭКСПЕДИТОРСКИХ УСЛУГ от {date}</strong></span></p>

<p style="text-align:center"><span style="font-size:14px"><strong>приложение к договору № {zakazchik.doc}</strong></span></p>

<p><span style="font-size:16px"><cite><strong>Заказчик : </strong></cite></span><strong>{zakazchik.name}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</strong></p>

<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:1040px">
	<tbody>
		<tr>
			<td style="height:40px; text-align:center; width:50px"><strong>1</strong></td>
			<td colspan="5" style="height:40px; text-align:center; width:200px"><span style="font-size:20px"><strong>Маршрут:</strong></span></td>
			<td colspan="8" rowspan="1" style="height:40px; text-align:center; width:200px"><span style="font-size:16px">{rout}</span></td>
		</tr>
		<tr>
			<td style="height:25px; text-align:center"><strong>2</strong></td>
			<td colspan="2" style="height:25px; text-align:center; width:50%">
			<p><span style="font-size:16px"><strong>Дата и время прибытия под&nbsp;загрузку:</strong></span></p>
			</td>
			<td colspan="4" rowspan="1" style="height:25px; text-align:center"><span style="font-size:16px"><strong>Контактное лицо на загрузке и Тел.</strong></span></td>
			<td colspan="7" rowspan="1" style="height:25px; text-align:center; width:150px"><span style="font-size:16px"><strong>Место прибытия под загрузку:</strong></span></td>
		</tr>
		<tr>
			<td style="height:60px; text-align:center"><strong>2.1</strong></td>
			<td colspan="2" style="height:25px; text-align:center; width:50px"><span style="font-size:16px">{shipping_date} -&nbsp;{shipping_date_2}</span></td>
			<td colspan="4" rowspan="1" style="height:25px; width:50px"><span style="font-size:16px">{telephone1}</span></td>
			<td colspan="7" rowspan="1" style="height:25px; width:100px"><span style="font-size:16px">{address1}</span></td>
		</tr>
		<tr>
			<td style="height:30px; text-align:center"><strong>3</strong></td>
			<td colspan="2" style="height:30px; text-align:center; width:50px"><span style="font-size:16px"><strong>Дата и время прибытия под разгрузку:</strong></span></td>
			<td colspan="4" rowspan="1" style="height:30px; text-align:center; width:50px"><span style="font-size:16px"><strong>Контактное лицо на разгрузке и Тел.</strong></span></td>
			<td colspan="7" rowspan="1" style="height:30px; text-align:center"><span style="font-size:16px"><strong>Место прибытия под разгрузку:</strong></span></td>
		</tr>
		<tr>
			<td style="height:60px; text-align:center"><strong>3.1</strong></td>
			<td colspan="2" style="height:25px; text-align:center; width:50px"><span style="font-size:16px">{date_out4} -&nbsp;{date_out4_2}</span></td>
			<td colspan="4" rowspan="1" style="height:25px; width:50px"><span style="font-size:16px">{telephone}</span></td>
			<td colspan="7" rowspan="1" style="height:25px"><span style="font-size:16px">{address_out4}</span></td>
		</tr>
		<tr>
			<td style="height:20px; text-align:center"><strong>4</strong></td>
			<td style="height:20px; text-align:center; width:50px"><strong><span style="font-size:16px">Наименование груза:</span></strong></td>
			<td style="height:20px; text-align:center; width:50px"><strong><span style="font-size:16px"><span style="font-family:sans-serif,arial,verdana,trebuchet ms">Вид автоперевозки:</span></span></strong></td>
			<td style="height:20px; text-align:center; width:50px"><strong><span style="font-size:16px">Упаковка:</span></strong></td>
			<td style="height:20px; text-align:center; width:200px"><strong><span style="font-size:16px">Тип загрузки/выгрузки:</span></strong></td>
			<td style="height:20px; text-align:center; width:100px"><span style="font-size:16px"><strong>Кузов</strong></span></td>
			<td colspan="2" rowspan="1" style="height:20px; text-align:center; width:200px"><strong><span style="font-size:16px">(ДШВ)</span></strong></td>
			<td style="height:20px; text-align:center; width:60px"><span style="font-size:16px"><strong>Вес(т):</strong></span></td>
			<td style="height:20px; text-align:center; width:60px"><span style="font-size:16px"><strong>Количество мест</strong></span></td>
			<td style="height:20px; text-align:center; width:60px"><span style="font-size:16px"><strong>Мз</strong></span></td>
			<td style="height:20px; text-align:center; width:150px"><span style="font-size:16px"><strong>Стоимость груза:</strong></span></td>
			<td colspan="3" rowspan="1" style="height:20px; text-align:center; width:200px"><strong><span style="font-size:16px">Страхование</span></strong></td>
		</tr>
		<tr>
			<td style="height:40px; text-align:center"><strong>4.1</strong></td>
			<td style="height:40px; text-align:center; width:50px"><span style="font-size:16px">{name}</span></td>
			<td style="height:40px; text-align:center; width:50px"><span style="font-size:16px">{view_auto}</span></td>
			<td style="height:40px; text-align:center; width:50px"><span style="font-size:16px">{name2}</span></td>
			<td style="height:40px; text-align:center"><span style="font-size:16px">{loading}</span></td>
			<td style="height:40px; text-align:center"><span style="font-family:sans-serif,arial,verdana,trebuchet ms; font-size:16px">{bodyType}</span></td>
			<td colspan="2" style="height:40px; text-align:center"><span style="font-size:16px">{length}-{width}-{height}</span></td>
			<td style="height:40px; text-align:center"><span style="font-size:16px">{cargo}</span></td>
			<td style="height:40px; text-align:center"><span style="font-size:16px">{place_count}</span></td>
			<td style="height:40px; text-align:center"><span style="font-size:16px">{volume}</span></td>
			<td style="height:40px; text-align:center"><span style="font-size:16px">{name_price}</span></td>
			<td colspan="3" rowspan="1" style="height:40px; text-align:center">{ensurance_yesno}</td>
		</tr>
		<tr>
			<td style="height:30px; text-align:center"><strong>5</strong></td>
			<td colspan="13" rowspan="1" style="height:30px; text-align:center"><span style="font-size:20px"><strong>Дополнительная&nbsp; информация о грузе:</strong></span></td>
		</tr>
		<tr>
			<td style="height:40px; text-align:center"><strong>5.1</strong></td>
			<td colspan="13" rowspan="1" style="height:40px"><span style="font-size:16px">{dop_informaciya_o_gruze}</span></td>
		</tr>
		<tr>
			<td style="height:30px; text-align:center"><strong>6</strong></td>
			<td colspan="13" rowspan="1" style="height:30px; text-align:center; width:120px"><span style="font-size:20px"><strong>Дополнительные условия</strong></span></td>
		</tr>
		<tr>
			<td style="height:40px; text-align:center"><strong>6.1</strong></td>
			<td colspan="13" rowspan="1" style="height:40px"><span style="font-size:16px">{otherwise4}&nbsp;</span></td>
		</tr>
		<tr>
			<td style="height:20px; text-align:center">7</td>
			<td colspan="7" rowspan="1" style="text-align:center; width:400px"><strong><span style="font-size:16px">ФИО Водителя, паспортные данные, В\У, телефон:</span></strong></td>
			<td colspan="6" style="text-align:center"><strong><span style="font-size:16px">Тип автомобиля, модель:</span></strong></td>
		</tr>
		<tr>
			<td style="height:20px; text-align:center">8</td>
			<td colspan="7" style="text-align:center; width:400px">{driver.data}&nbsp;{driver.driver}&nbsp;Тел. {driver.phone}</td>
			<td colspan="6" style="text-align:center">{autoString}</td>
		</tr>
		<tr>
			<td style="height:30px; text-align:center"><strong>9</strong></td>
			<td colspan="8" rowspan="1" style="height:30px; text-align:center">
			<p><strong>Размер и форма оплаты:</strong><big><cite><span style="font-size:16px">{we}, {pay_us}, {col2} б/д&nbsp;{payment1}{cardString}</span></cite></big></p>
			</td>
			<td colspan="5" rowspan="1" style="height:30px; text-align:justify">
			<p><strong><span style="font-size:16px">Менеджер :</span></strong>&nbsp;Тел.-<span style="font-family:sans-serif,arial,verdana,trebuchet ms">{user.phone}&nbsp;&nbsp;</span></p>
			</td>
		</tr>
		<tr>
			<td style="text-align:center"><strong>10</strong></td>
			<td colspan="13" rowspan="1">
			<p>10.1. Настоящая Договор-Заявка (далее - Заявка) имеет силу договора разовой перевозки груза, если ранее не предусмотрен основополагающий договор.</p>

			<p>10.2. Стороны подтверждают и признают, что факсимильная (и) или электронная копия Заявки подписанная, скрепленная подписью сторон, равно как и все приложения к ней и все документы по ее исполнению действительны и имеют юридическую силу до получения оригиналов данных документов.</p>

			<p>10.3. Обязательное условие &ndash; соблюдение ГК РФ (гл. 40.41 ст.801); Постановление Правительства РФ № 554 от 08.09.06г., Приказа № 23 МТ РФ от 11.02.2008г., № 259-ФЗ &laquo;Устав автомобильного транспорта и городского наземного электрического транспорта&raquo; гл. 6 ст. 34, 35, № 87-ФЗ &laquo; О транспортно-экспедиционной деятельности.</p>

			<p>10.4. Штрафы за сверхнормативный простой под погрузкой/разгрузкой, а также опоздание ТС на погрузку/разгрузку рассчитываются (на основании отметки указанной в товарно&ndash;транспортном (далее &ndash; ТН/ТТН) документе о времени их убытия и прибытия).</p>

			<p>10.5 Экспедитор вправе удерживать перевозимый груз Заказчика до момента оплаты Заказчиком услуг Экспедитора по настоящей Заявке в согласованном Сторонами размере (право удержания &ndash; ст. 359-360 ГК РФ, п. 3 ст. 3 ФЗ от 30.06.2003 N 87-ФЗ &quot;О транспортно-экспедиционной деятельности&quot;). В случае отсутствия оплаты Заказчиком услуг, Экспедитор на момент доставки груза в пункт назначения, вправе разместить груз на хранение за счет Заказчика.</p>

			<p>10.6. Стороны приняли, что возмещение понесенных убытков и неустойки может производиться путем зачета встречных однородных требований в порядке ст. 410 ГК РФ с направлением другой Стороне соответствующего уведомления.</p>

			<p>10.7. Обо всех возникающих задержках, при допуске на погрузку/разгрузку, угрозах сохранности груза, перегрузе, не выдачи ТН/ТТН на загрузке, отклонение от подписанной заявки и прочее, составленных Актах Заказчик/Экспедитор обязан известить Заказчика/Экспедитора НЕЗАМЕДЛИТЕЛЬНО, не зависимо от времени суток после возникновения таких обстоятельств.</p>

			<p>10.8. Заказчик обязуется до начала погрузки произвести сверку документов, предъявленных водителем (паспорт, ПТС, водительское удостоверение) с данными, указанными в Договоре-заявке, произвести проверку указанных документов на предмет подлинности (переклейка фотографии, подделка бланков и пр.), а также снять копии с указанных документов. В случае выявления несоответствий Заказчик должен незамедлительно сообщить о данном факте менеджеру/диспетчеру Экспедитора.</p>
			</td>
		</tr>
		<tr>
			<td style="text-align:center"><strong>11</strong></td>
			<td colspan="13">
			<p><strong>ШТРАФНЫЕ САНКЦИИ</strong></p>

			<p><strong>11.1. Заказчик оплачивает Экспедитору:</strong></p>

			<p>- штраф в размере 20% (Двадцать) от стоимости перевозки в случае отказа (срыва), не предоставление Заказчиком груза или сопроводительных документов обусловленного Заявкой, необходимых для осуществления перевозки.</p>

			<p>- заказчик обязуется произвести погрузочно-разгрузочные работы в течение 12-ти (двенадцать) часов с момента прибытия транспортного средства под погрузку/выгрузку.</p>

			<p>- за простой (задержку) ТС, поданных под погрузку/выгрузку в размере 2000,00 (Две тысячи) рублей за каждые сутки (в том числе не полные) такой задержки.</p>

			<p>- за просрочку оплаты услуг Экспедитора, Заказчик уплачивает неустойку в виде пени в размере 0,1 % от неуплаченной суммы за каждый календарный день просрочки по день фактической уплаты долга.</p>

			<p>- в случае отклонения от Заявки без согласования с Экспедитором (менеджером/диспетчером) взымается штраф в размере 2000 (Две тысячи) рублей.</p>

			<p><strong>11.2. Экспедитор оплачивает заказчику:</strong></p>

			<p>- штраф в размере 20% (Двадцать) от стоимости перевозки в случае отказа (срыва) от принятой к исполнению заявки Заказчика, а также в случае отказа от Заявки, подписанной обеими сторонами.</p>

			<p>Исключение являются случаи, когда документально доказано, что срыв погрузки/разгрузки возник по причине дорожно-транспортного происшествия (далее ДТП), произошедшего не по вине водителя и подтверждённого официальными документами соответствующих органов.</p>
			- за опоздание ТС к месту погрузки/разгрузки в размере 2 000,00 (Две тысячи) рублей за каждый полный день опоздания.</td>
		</tr>
		<tr>
			<td style="text-align:center">
			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			<p>12</p>
			</td>
			<td colspan="13">
			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">&nbsp;</p>

			<p style="margin-left:0.15pt">Сторона, получившая претензию, обязана рассмотреть её и направить другой стороне ответ. Срок ответа на претензию, должен быть дан в течении 10 (десяти) дней, с момента её получения по электронной почте, либо иным фиксированным способом.</p>

			<p>Если после направления претензии спор не был урегулирован, а Сторона, нарушившая обязательство, не устранила допущенные нарушения, или ответила отказом от удовлетворения претензии, спор может быть передан на рассмотрение в Арбитражный суд в Самарской области.</p>

			<p>&nbsp;</p>
			</td>
		</tr>
	</tbody>
</table>

<table border="1" cellpadding="1" cellspacing="1" style="width:1040px">
	<tbody>
		<tr>
			<td colspan="2" rowspan="1" style="text-align:center; width:525px"><span style="font-size:18px"><strong>Заказчик: {zakazchik.name}</strong></span></td>
			<td colspan="2" rowspan="1" style="text-align:center; width:525px"><span style="font-size:18px"><strong>Экспедитор: {organization.name}</strong></span></td>
		</tr>
		<tr>
			<td style="text-align:center; width:80px"><strong>ИНН</strong></td>
			<td style="text-align:center">{zakazchik.inn}&nbsp;</td>
			<td style="text-align:center; width:80px"><strong>ИНН</strong></td>
			<td style="text-align:center">{organization.inn}</td>
		</tr>
		<tr>
			<td style="text-align:center"><strong>Юридический адрес</strong></td>
			<td style="text-align:center">{zakazchik.official_address}</td>
			<td style="text-align:center"><strong>Юридический адрес</strong></td>
			<td style="text-align:center">{organization.official_address}</td>
		</tr>
		<tr>
			<td style="text-align:center"><strong>Почтовый адрес</strong></td>
			<td style="text-align:center">{zakazchik.mailing_address}</td>
			<td style="text-align:center"><strong>Почтовый адрес</strong></td>
			<td style="text-align:center">{user.post_address}</td>
		</tr>
		<tr>
			<td style="text-align:center"><strong>Банк</strong></td>
			<td style="text-align:center">{zakazchik.bank_name}</td>
			<td style="text-align:center"><strong>Банк</strong></td>
			<td style="text-align:center">{organization.bank_name}</td>
		</tr>
		<tr>
			<td style="text-align:center"><strong>р/сч</strong></td>
			<td style="text-align:center">{zakazchik.nomer_rascheta}</td>
			<td style="text-align:center"><strong>р/сч</strong></td>
			<td style="text-align:center">{organization.nomer_rascheta}</td>
		</tr>
		<tr>
			<td style="text-align:center"><strong>к/сч</strong></td>
			<td style="text-align:center">{zakazchik.kr}</td>
			<td style="text-align:center"><strong>к/сч</strong></td>
			<td style="text-align:center">{organization.kr}</td>
		</tr>
		<tr>
			<td style="text-align:center"><strong>БИК</strong></td>
			<td style="text-align:center">{zakazchik.bic}</td>
			<td style="text-align:center"><strong>БИК</strong></td>
			<td style="text-align:center">{organization.bic}</td>
		</tr>
	</tbody>
</table>

<table cellpadding="1" cellspacing="1" style="width:1050px">
	<tbody>
		<tr>
			<td style="width:525px">{zakazchik.doljnost_rukovoditelya} :&nbsp;{zakazchik.name}</td>
			<td>{organization.doljnost_rukovoditelya}&nbsp;:&nbsp;<span style="font-family:sans-serif,arial,verdana,trebuchet ms">&nbsp;</span>{organization.name}<strong>&nbsp;</strong></td>
		</tr>
	</tbody>
</table>

<table cellpadding="1" cellspacing="1" style="width:1050px">
	<tbody>
		<tr>
			<td style="width:525px">{zakazchik.fio_polnostyu}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<span style="font-family:sans-serif,arial,verdana,trebuchet ms">____________(подпись)</span><span style="font-family:sans-serif,arial,verdana,trebuchet ms">&nbsp; &nbsp;&nbsp;</span><span style="font-family:sans-serif,arial,verdana,trebuchet ms">М.П</span></td>
			<td>{organization.fio_buhgaltera}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span style="font-family:sans-serif,arial,verdana,trebuchet ms">&nbsp;____________(подпись)</span><span style="font-family:sans-serif,arial,verdana,trebuchet ms">&nbsp; &nbsp;&nbsp;</span><span style="font-family:sans-serif,arial,verdana,trebuchet ms">М.П</span></td>
		</tr>
	</tbody>
</table>

<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>

<p><span style="background-color:rgb(255, 255, 255); color:rgb(51, 51, 51); font-family:sans-serif,arial,verdana,trebuchet ms; font-size:13px">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span></p>
'
                    
            
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240916_145546_insert_template_client_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240916_145546_insert_template_client_table cannot be reverted.\n";

        return false;
    }
    */
}
