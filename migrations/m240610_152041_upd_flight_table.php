<?php

use yii\db\Migration;

/**
 * Class m240610_152041_upd_flight_table
 */
class m240610_152041_upd_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("flight", "file", 'LONGTEXT'); // NULL 
        $this->alterColumn("flight", "file_provider", 'LONGTEXT'); // NULL 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
     //   echo "m240610_152041_upd_flight_table cannot be reverted.\n";

       // return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240610_152041_upd_flight_table cannot be reverted.\n";

        return false;
    }
    */
}
