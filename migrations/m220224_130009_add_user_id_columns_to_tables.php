<?php

use yii\db\Migration;

/**
 * Class m220224_130009_add_user_id_columns_to_tables
 */
class m220224_130009_add_user_id_columns_to_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addUserColumn('flight');
        $this->addUserColumn('driver');
        $this->addUserColumn('client');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropUserColumn('flight');
        $this->dropUserColumn('driver');
        $this->dropUserColumn('client');
    }

    private function addUserColumn($table)
    {
        $this->addColumn($table, 'user_id', $this->integer()->comment('Пользователь'));

        $this->createIndex(
            "idx-{$table}-user_id",
            "{$table}",
            "user_id"
        );

        $this->addForeignKey(
            "fk-{$table}-user_id",
            "{$table}",
            "user_id",
            "user",
            "id",
            "SET NULL"
        );
    }

    private function dropUserColumn($table)
    {
        $this->dropForeignKey(
            "fk-{$table}-user_id",
            "{$table}"
        );

        $this->dropIndex(
            "idx-{$table}-user_id",
            "{$table}"
        );

        $this->dropColumn($table, 'user_id');
    }
}
