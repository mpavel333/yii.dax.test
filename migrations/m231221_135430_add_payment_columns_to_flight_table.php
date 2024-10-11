<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m231221_135430_add_payment_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_recoil_payment', $this->boolean()->defaultValue(false)->comment('Баллы'));
        $this->addColumn('flight', 'is_ensurance_payment', $this->boolean()->defaultValue(false)->comment('Страховка'));
        $this->addColumn('flight', 'is_additional_credit_payment', $this->boolean()->defaultValue(false)->comment('Доп. Расходы'));

        $this->addColumn('role', 'flight_btn_signature', $this->boolean()->defaultValue(false)->comment('Иконка «Подпись»'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_btn_signature');
        
        $this->dropColumn('flight', 'is_recoil_payment');
        $this->dropColumn('flight', 'is_ensurance_payment');
        $this->dropColumn('flight', 'is_additional_credit_payment');
    }
}
