<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240211_120837_add_checks_columns_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_check_salary', $this->boolean()->defaultValue(false)->comment("Галочка оплаты"));
        $this->addColumn('role', 'flight_check_recoil', $this->boolean()->defaultValue(false)->comment("Галочка баллов"));
        $this->addColumn('role', 'flight_check_insurance', $this->boolean()->defaultValue(false)->comment("Галочка страховки"));
        $this->addColumn('role', 'flight_check_additional_credit', $this->boolean()->defaultValue(false)->comment("Галочка доп. расходов"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_check_salary');
        $this->dropColumn('role', 'flight_check_recoil');
        $this->dropColumn('role', 'flight_check_insurance');
        $this->dropColumn('role', 'flight_check_additional_credit');
    }
}
