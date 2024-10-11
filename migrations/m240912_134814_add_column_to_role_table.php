<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240912_134814_add_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'client_control_all', $this->boolean()->comment('Контроль всех'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
