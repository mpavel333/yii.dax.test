<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%car}}`.
 */
class m220830_120011_add_osago_columns_to_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('car', 'osago_number', $this->string()->comment('Номер ОСАГО'));
        $this->addColumn('car', 'osago_date_start', $this->date()->comment('Дата начала ОСАГО'));
        $this->addColumn('car', 'osago_date_end', $this->date()->comment('Дата окончания ОСАГО'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('car', 'osago_number');
        $this->dropColumn('car', 'osago_date_start');
        $this->dropColumn('car', 'osago_date_end');        
    }
}
