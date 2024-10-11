<?php

use yii\db\Migration;

/**
 * Class m240206_110517_alter_addres_columns_in_flight_table
 */
class m240206_110517_alter_addres_columns_in_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('flight', 'address1', $this->text()->comment('Адрес загрузки'));
        $this->alterColumn('flight', 'address_out4', $this->text()->comment('Адрес разгрузки'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
