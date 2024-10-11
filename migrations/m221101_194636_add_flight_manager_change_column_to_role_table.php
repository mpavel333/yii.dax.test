<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m221101_194636_add_flight_manager_change_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_manager_change', $this->boolean()->defaultValue(false)->comment('Изменение менеджера'));
        $this->addColumn('role', 'flight_order', $this->boolean()->defaultValue(false)->comment('Поле "Заявка"'));
        $this->addColumn('role', 'flight_driver_order', $this->boolean()->defaultValue(false)->comment('Поле "Заявка перевозчика"'));
        $this->addColumn('role', 'flight_driver_signature', $this->boolean()->defaultValue(false)->comment('Галочка "Подпись перевозчика"'));
        $this->addColumn('role', 'flight_orders_show', $this->boolean()->defaultValue(false)->comment('Раздел "Заявки"'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_manager_change');
        $this->dropColumn('role', 'flight_order');
        $this->dropColumn('role', 'flight_driver_order');
        $this->dropColumn('role', 'flight_driver_signature');
        $this->dropColumn('role', 'flight_orders_show');
    }
}
