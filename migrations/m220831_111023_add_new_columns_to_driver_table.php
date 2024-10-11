<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%driver}}`.
 */
class m220831_111023_add_new_columns_to_driver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('driver', 'car_number', $this->string()->comment('Номер автомобиля'));
        $this->addColumn('driver', 'car_truck_number', $this->string()->comment('Номер прицепа'));
        $this->addColumn('flight', 'car_number', $this->string()->comment('Номер автомобиля'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('driver', 'car_number');
        $this->dropColumn('driver', 'car_truck_number');
        $this->dropColumn('flight', 'car_number');
    }
}
