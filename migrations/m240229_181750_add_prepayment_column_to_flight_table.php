<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240229_181750_add_prepayment_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'we_prepayment_form', $this->double()->comment('Аванс'));
        $this->addColumn('flight', 'payment_out_prepayment_form', $this->double()->comment('Аванс'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'we_prepayment_form');
        $this->dropColumn('flight', 'payment_out_prepayment_form');
    }
}
