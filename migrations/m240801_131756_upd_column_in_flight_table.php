<?php

use yii\db\Migration;

/**
 * Class m240801_131756_upd_column_in_flight_table
 */
class m240801_131756_upd_column_in_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("flight", "number_prepayed", 'string'); // NULL 

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240801_131756_upd_column_in_flight_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240801_131756_upd_column_in_flight_table cannot be reverted.\n";

        return false;
    }
    */
}
