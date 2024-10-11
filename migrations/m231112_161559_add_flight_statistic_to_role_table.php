<?php

use yii\db\Migration;

/**
 * Class m231112_161559_add_flight_statistic_to_role_table
 */
class m231112_161559_add_flight_statistic_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_statistic', $this->boolean()->defaultValue(false)->comment('Статистика'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_statistic');
    }
}
