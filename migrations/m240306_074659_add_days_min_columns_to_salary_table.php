<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%salary}}`.
 */
class m240306_074659_add_days_min_columns_to_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('salary', 'day_pays_min', $this->integer()->comment('Дни оплаты минимальные'));
        $this->addColumn('salary', 'day_pays_between', $this->integer()->comment('Дни оплаты между заказчиком и перевозчиком'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('salary', 'day_pays_min');
        $this->dropColumn('salary', 'day_pays_between');
    }
}
