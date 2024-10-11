<?php

use yii\db\Migration;

/**
 * Class m240618_141220_add_truck_number_to_car_table
 */
class m240618_141220_add_truck_number_to_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('car', 'truck_number', $this->string()->after('number')->comment('Гос. номер прицепа'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240618_141220_add_truck_number_to_car_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240618_141220_add_truck_number_to_car_table cannot be reverted.\n";

        return false;
    }
    */
}
