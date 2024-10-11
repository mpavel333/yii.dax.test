<?php

use yii\db\Migration;

/**
 * Class m240625_081845_add_new_columns_to_role
 */
class m240625_081845_add_new_columns_to_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'car_to_responsible', $this->boolean()->comment('Автопарк ТО Ответственный'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240625_081845_add_new_columns_to_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240625_081845_add_new_columns_to_role cannot be reverted.\n";

        return false;
    }
    */
}
