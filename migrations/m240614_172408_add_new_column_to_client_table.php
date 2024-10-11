<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%client}}`.
 */
class m240614_172408_add_new_column_to_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'contract', $this->boolean()->defaultValue(false)->comment('Договор чекбокс'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
