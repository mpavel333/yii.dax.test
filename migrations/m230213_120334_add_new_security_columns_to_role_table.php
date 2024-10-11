<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m230213_120334_add_new_security_columns_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'security_table', $this->boolean()->defaultValue(false)->comment('Безопасность'));
        $this->addColumn('role', 'login', $this->boolean()->defaultValue(false)->comment('Входы'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'security_table');
        $this->dropColumn('role', 'login');
    }
}
