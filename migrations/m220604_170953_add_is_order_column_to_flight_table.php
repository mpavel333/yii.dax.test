<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m220604_170953_add_is_order_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_order', $this->boolean()->defaultValue(false)->comment('Заявка'));
        $this->addColumn('flight', 'is_signature', $this->boolean()->defaultValue(false)->comment('Подпись'));
        $this->addColumn('role', 'flight_is_order', $this->boolean()->defaultValue(false)->comment('Заявка'));
        $this->addColumn('role', 'flight_is_signature', $this->boolean()->defaultValue(false)->comment('Подпись'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_is_order');
        $this->dropColumn('role', 'flight_is_signature');
        $this->dropColumn('flight', 'is_order');
        $this->dropColumn('flight', 'is_signature');
    }
}
