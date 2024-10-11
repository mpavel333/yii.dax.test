<?php

use yii\db\Migration;

/**
 * Class m240910_123004_update_template_table
 */
class m240910_123004_update_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('template', ['text' => '@app/views/flight/_prepayment.php','modifier'=>'PREPAYMENT'], ['id' => 28]);
        
        $this->insert('template',array(
            'name' =>'Счёт/Аванс',
            'type'=>432,
            'text'=>'<table border="1" cellpadding="1" cellspacing="2" style="height:100px; width:1400px">
	<tbody>
		<tr>
			<td colspan="2" style="height:50px"><span style="font-size:22px">Получатель: {organization.name}</span></td>
			<td><span style="font-size:22px">БИК</span></td>
			<td><span style="font-size:22px">{organization.bic}</span></td>
		</tr>
		<tr>
			<td style="height:50px"><span style="font-size:22px">ИНН {organization.inn}</span></td>
			<td><span style="font-size:22px">КПП {organization.kpp}</span></td>
			<td><span style="font-size:22px">Сч.</span></td>
			<td><span style="font-size:22px">{organization.nomer_rascheta}</span></td>
		</tr>
		<tr>
			<td colspan="2" style="height:100px">
			<p><span style="font-size:22px">Банк получателя :</span></p>

			<p>&nbsp;</p>

			<p><span style="font-size:22px">{organization.bank_name}</span></p>

			<p>&nbsp;</p>
			</td>
			<td><span style="font-size:22px">Кор.</span></td>
			<td><span style="font-size:22px">{organization.kr}</span></td>
		</tr>
	</tbody>
</table>


<h1 style="text-align:center; width: 1400px;"></h1>

<h1 style="text-align:center; width: 1400px;"><strong><span style="font-size:36px">Счёт №{number_prepayed}</span></strong></h1>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p><span style="font-size:18px">Поставщик (Исполнитель): </span></p>

<p>&nbsp;</p>

<p><span style="font-size:22px">{organization.name} ИНН:&nbsp;{organization.inn} </span></p>

<p>&nbsp;</p>

<p><span style="font-size:22px">Юр.Адрес:&nbsp;{organization.official_address}</span></p>

<h2 style="font-style:italic">&nbsp;</h2>

<p>&nbsp;</p>

<p><span style="font-size:18px">Покупатель (Заказчик)</span></p>

<p>&nbsp;</p>

<p><span style="font-size:22px">{zakazchik.name} ИНН:{zakazchik.inn} </span></p>

<p>&nbsp;</p>

<p><span style="font-size:22px">Юр.Адрес: {zakazchik.official_address} </span></p>

<p>&nbsp;</p>

<p><span style="font-size:22px">Основание: {zakazchik.doc}</span></p>

<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:1400px">
	<tbody>
		<tr>
			<td style="height:30px; text-align:center"><strong><span style="font-size:24px">№</span></strong></td>
			<td style="text-align:center; width:400px"><strong><span style="font-size:24px">Наименование работ, услуг</span></strong></td>
			<td style="text-align:center; width:100px"><strong><span style="font-size:24px">Кол-вo</span></strong></td>
			<td style="text-align:center"><strong><span style="font-size:24px">Ед</span></strong></td>
			<td style="text-align:center"><strong><span style="font-size:24px">Цена</span></strong></td>
			<td style="text-align:center"><strong><span style="font-size:24px">Сумма</span></strong></td>
		</tr>
		<tr>
			<td style="height:90px; text-align:center"><span style="font-size:24px">1</span></td>
			<td style="text-align:justify;width: 560px;">
			<h3 style="text-align:center"><strong><span style="font-size:22px; line-height: 22px;">{tableName}</span></strong></h3>
			</td>
			<td style="text-align:center"><span style="font-size:24px">{flightsCount}</span></td>
			<td style="text-align:center"><span style="font-size:24px">Рейс</span></td>
			<td style="text-align:center"><span style="font-size:24px">{price}</span></td>
			<td style="text-align:center"><span style="font-size:24px">{we_prepayment_form}</span></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>&nbsp;</p>

<div style="display: table; width: 100%;">
	<div style="display: table-cell; width: 55%;">

<p><strong><span style="font-size:22px">Всего наименований 1, на сумму&nbsp;{price}&nbsp;</span></strong></p>

<p>&nbsp;</p>

<p><strong><span style="font-size:22px">{priceString}&nbsp;</span></strong></p>

<p>&nbsp;</p>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><strong><span style="font-size:20px">Руководитель _______{organization.fio_polnostyu}</span></strong></p>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<p><strong><span style="font-size:20px">Бухгалтер _______{organization.fio_buhgaltera}</span></strong></p>

	</div>
	<div style="display: table-cell; width: 30%;">

<p style="margin-bottom: 14px;"><strong><span style="font-size:20px;"><span style="font-size:24px">Итого: {we_prepayment_form} {pay_us}</span></span></strong></p>


<p class="nds-line" style="margin-bottom: 14px;"><strong><span style="font-size:20px;"><span style="font-size:24px">{ndsText}&nbsp; &nbsp;</span></span></strong></p>


<p style="margin-bottom: 14px;"><strong><span style="font-size:20px;"><span style="font-size:24px">Всего к оплате: {we_prepayment_form} {pay_us}</span></span></strong></p>

<p>&nbsp;</p>



	</div>
</div>	


'
                    
            
        ));
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240910_123004_update_template_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240910_123004_update_template_table cannot be reverted.\n";

        return false;
    }
    */
}
