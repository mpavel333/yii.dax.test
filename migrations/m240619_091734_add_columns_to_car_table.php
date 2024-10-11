<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%car}}`.
 */
class m240619_091734_add_columns_to_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('car', 'phone', $this->string()->after('truck_number')->comment('Водитель Телефон'));
        $this->addColumn('car', 'driver', $this->binary()->after('phone')->comment('Водитель паспортные данные'));


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
