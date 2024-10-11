<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240717_110403_add_lawyer_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'lawyer', $this->boolean()->defaultValue(false)->comment('Юристы'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'lawyer');
    }
}
