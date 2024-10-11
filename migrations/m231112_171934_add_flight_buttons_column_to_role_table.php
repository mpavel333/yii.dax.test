<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m231112_171934_add_flight_buttons_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_btn_print', $this->boolean()->defaultValue(false)->comment('Иконка «Печать»'));
        $this->addColumn('role', 'flight_btn_update', $this->boolean()->defaultValue(false)->comment('Иконка «Редактировать»'));
        $this->addColumn('role', 'flight_btn_export', $this->boolean()->defaultValue(false)->comment('Иконка «Экспорт»'));
        $this->addColumn('role', 'flight_btn_print_pdf', $this->boolean()->defaultValue(false)->comment('Иконка «Печать PDF»'));
        $this->addColumn('role', 'flight_btn_copy', $this->boolean()->defaultValue(false)->comment('Иконка «Копировать»'));
        $this->addColumn('role', 'flight_btn_delete', $this->boolean()->defaultValue(false)->comment('Иконка «Удалить»'));
        $this->addColumn('role', 'flight_btn_archive', $this->boolean()->defaultValue(false)->comment('Иконка «Архив»'));
        $this->addColumn('role', 'flight_btn_api', $this->boolean()->defaultValue(false)->comment('Иконка «API»'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_btn_print');
        $this->dropColumn('role', 'flight_btn_update');
        $this->dropColumn('role', 'flight_btn_export');
        $this->dropColumn('role', 'flight_btn_print_pdf');
        $this->dropColumn('role', 'flight_btn_copy');
        $this->dropColumn('role', 'flight_btn_delete');
        $this->dropColumn('role', 'flight_btn_archive');
        $this->dropColumn('role', 'flight_btn_api');
    }
}
