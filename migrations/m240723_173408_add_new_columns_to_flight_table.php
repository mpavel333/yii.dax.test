<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240723_173408_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_payed_date', $this->dateTime()->after('is_payed')->comment('Дата оплаты'));
        $this->addColumn('flight', 'is_driver_payed_date', $this->dateTime()->after('is_driver_payed')->comment('Дата оплаты водителя'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
