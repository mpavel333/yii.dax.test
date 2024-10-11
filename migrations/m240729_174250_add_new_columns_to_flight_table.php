<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240729_174250_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_we_prepayment', $this->boolean()->comment('Аванс от заказчика'));
        $this->addColumn('flight', 'date_we_prepayment', $this->datetime()->comment('Дата аванс от заказчика'));

        $this->addColumn('flight', 'is_payment_out_prepayment', $this->boolean()->comment('Аванс от водителя'));
        $this->addColumn('flight', 'date_payment_out_prepayment', $this->datetime()->comment('Дата аванс от заказчика'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
