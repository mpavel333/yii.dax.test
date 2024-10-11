<?php

use yii\db\Migration;

/**
 * Class m240612_143839_upd_client_table
 */
class m240612_143839_upd_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("client", "file", 'LONGTEXT'); // NULL 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240612_143839_upd_client_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240612_143839_upd_client_table cannot be reverted.\n";

        return false;
    }
    */
}
