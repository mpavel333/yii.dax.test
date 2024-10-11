<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%salary}}`.
 */
class m240227_180133_add_delta_percent_no_nds_column_to_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('salary', 'delta_percent_no_nds', $this->double()->comment('Процент дельты без НДС'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('salary', 'delta_percent_no_nds');
    }
}
