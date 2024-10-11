<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%requisite}}`.
 */
class m230327_094313_add_is_hidden_column_to_requisite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('requisite', 'is_hidden', $this->boolean()->defaultValue(false)->comment('Скрытое'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('requisite', 'is_hidden');
    }
}
