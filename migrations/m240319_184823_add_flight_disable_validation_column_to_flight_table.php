<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240319_184823_add_flight_disable_validation_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_disable_validation', $this->boolean()->defaultValue(false)->comment('Отключить валидацию'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_disable_validation');
    }
}
