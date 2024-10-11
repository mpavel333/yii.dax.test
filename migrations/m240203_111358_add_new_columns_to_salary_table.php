<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%salary}}`.
 */
class m240203_111358_add_new_columns_to_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('salary', 'delta_percent', $this->double()->comment('Процент дельты'));
        $this->addColumn('salary', 'delta_percent_additional', $this->double()->comment('Процент дельты (Доп расходы)'));
        $this->addColumn('salary', 'delta_recoil', $this->double()->comment('Процент Балла'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('salary', 'delta_percent');
        $this->dropColumn('salary', 'delta_percent_additional');
        $this->dropColumn('salary', 'delta_recoil');
    }
}
