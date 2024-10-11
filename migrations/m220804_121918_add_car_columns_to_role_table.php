<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m220804_121918_add_car_columns_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'car', $this->boolean()->defaultValue(false)->comment('Автопарк'));
        $this->addColumn('role', 'flight_table', $this->boolean()->defaultValue(false)->comment('Настройки таблицы'));
        $this->addColumn('role', 'flight_dates', $this->boolean()->defaultValue(false)->comment('Даты'));
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_dates');
        $this->dropColumn('role', 'flight_table');
        $this->dropColumn('role', 'car');
    }
}
