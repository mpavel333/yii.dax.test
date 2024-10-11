<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m230923_161150_add_group_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('user', 'group', $this->string()->comment('Группа'));
    	$this->addColumn('role', 'flight_group_table', $this->boolean()->defaultValue(false)->comment('Группа'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropColumn('user', 'group');
    	$this->dropColumn('role', 'flight_group_table');
    }
}
