<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240407_182205_add_new_columns_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_checks1', $this->boolean()->defaultValue(false)->comment('Документы 1'));
        $this->addColumn('role', 'flight_checks2', $this->boolean()->defaultValue(false)->comment('Документы 2'));
        $this->addColumn('role', 'flight_checks3', $this->boolean()->defaultValue(false)->comment('Документы 3'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_checks1');
        $this->dropColumn('role', 'flight_checks2');
        $this->dropColumn('role', 'flight_checks3');
    }
}
