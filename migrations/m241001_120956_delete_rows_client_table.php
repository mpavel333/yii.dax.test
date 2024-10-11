<?php

use yii\db\Migration;

/**
 * Class m241001_120956_delete_rows_client_table
 */
class m241001_120956_delete_rows_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('client', []);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241001_120956_delete_rows_client_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241001_120956_delete_rows_client_table cannot be reverted.\n";

        return false;
    }
    */
}
