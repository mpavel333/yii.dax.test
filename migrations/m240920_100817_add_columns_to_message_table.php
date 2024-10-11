<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%message}}`.
 */
class m240920_100817_add_columns_to_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('message', 'user_id_delete', $this->boolean()->after('user_id')->comment('Удалено у пользователя'));
        $this->addColumn('message', 'user_to_id_delete', $this->boolean()->after('user_to_id')->comment('Удалено у получателя'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
