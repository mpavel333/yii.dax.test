<?php

use yii\db\Migration;

/**
 * Class m230924_143506_alter_percent_salary_column_from_user_table
 */
class m230924_143506_alter_percent_salary_column_from_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'percent_salary', $this->string()->comment('Процент заплаты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user', 'percent_salary', $this->double()->comment('Процент заплаты'));
    }
}
