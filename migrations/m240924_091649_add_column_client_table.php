<?php

use yii\db\Migration;

/**
 * Class m240924_091649_add_column_client_table
 */
class m240924_091649_add_column_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'tabel', $this->string()->comment('Табель'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240924_091649_add_column_client_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240924_091649_add_column_client_table cannot be reverted.\n";

        return false;
    }
    */
}
