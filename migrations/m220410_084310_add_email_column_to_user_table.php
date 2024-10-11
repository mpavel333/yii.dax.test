<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m220410_084310_add_email_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'email', $this->string()->after('login')->comment('Email'));
        $this->addColumn('user', 'percent', $this->float()->comment('Процент'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'email');
        $this->dropColumn('user', 'percent');
    }
}
