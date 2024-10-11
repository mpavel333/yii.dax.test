<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m231222_113420_add_dates_prepayment_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'index', $this->integer()->after('id')->comment('Порядковый номер'));

        $this->addColumn('flight', 'we_prepayment_datetime', $this->dateTime()->after('we_prepayment')->comment('Дата предоплаты заказчика'));
        $this->addColumn('flight', 'payment_out_prepayment_datetime', $this->dateTime()->after('payment_out_prepayment')->comment('Дата предоплаты водителя'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'we_prepayment_datetime');
        $this->dropColumn('flight', 'payment_out_prepayment_datetime');

        $this->dropColumn('flight', 'index');
    }
}
