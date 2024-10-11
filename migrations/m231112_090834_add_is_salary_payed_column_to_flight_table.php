<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m231112_090834_add_is_salary_payed_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_salary_payed', $this->boolean()->defaultValue(false)->comment('Зарплата оплачена'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('flight', 'is_salary_payed');
    }
}
