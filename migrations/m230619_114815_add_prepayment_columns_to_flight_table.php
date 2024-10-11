<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m230619_114815_add_prepayment_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'we_prepayment', $this->double()->comment('Предоплата от заказчика'));
        $this->addColumn('flight', 'payment_out_prepayment', $this->double()->comment('Предоплата от водителя'));

        $this->addColumn('role', 'flight_prepayment', $this->boolean()->defaultValue(false)->comment('Предоплата'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_prepayment');
     
        $this->dropColumn('flight', 'we_prepayment');
        $this->dropColumn('flight', 'payment_out_prepayment');
    }
}
