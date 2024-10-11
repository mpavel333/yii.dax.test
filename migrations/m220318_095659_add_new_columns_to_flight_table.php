<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m220318_095659_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'salary', $this->double()->comment('Зарплата'));
        $this->addColumn('flight', 'delta', $this->double()->comment('Дельта'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'salary');
        $this->dropColumn('flight', 'delta');
    }
}
