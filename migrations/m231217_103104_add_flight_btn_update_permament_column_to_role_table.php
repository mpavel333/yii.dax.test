<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m231217_103104_add_flight_btn_update_permament_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_btn_update_permament', $this->boolean()->defaultValue(false)->comment('Иконка «Редактировать» (Постоянная)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_btn_update_permament');
    }
}
