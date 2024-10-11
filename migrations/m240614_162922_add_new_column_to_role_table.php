<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240614_162922_add_new_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'client_contract', $this->boolean()->defaultValue(false)->comment('Организации Договор'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
