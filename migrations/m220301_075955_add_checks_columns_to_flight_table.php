<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m220301_075955_add_checks_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_driver_payed', $this->boolean()->defaultValue(false)->comment('Оплата водителя'));
        $this->addColumn('flight', 'is_ati_driver', $this->boolean()->defaultValue(false)->comment('АТИ водителя'));
        $this->addColumn('flight', 'is_ati_client', $this->boolean()->defaultValue(false)->comment('АТИ клиента'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColum('flight', 'is_driver_payed');
        $this->dropColum('flight', 'is_ati_driver');
        $this->dropColum('flight', 'is_ati_client');
    }
}
