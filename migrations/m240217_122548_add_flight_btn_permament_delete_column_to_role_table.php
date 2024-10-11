<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240217_122548_add_flight_btn_permament_delete_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_btn_permament_delete', $this->boolean()->defaultValue(false)->after('flight_btn_delete')->comment('Иконка «Удалить» (Постоянная)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_btn_permament_delete');
    }
}
