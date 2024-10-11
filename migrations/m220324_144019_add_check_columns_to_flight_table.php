<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m220324_144019_add_check_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'order_check', $this->boolean()->defaultValue(false)->after('order'));
        $this->addColumn('flight', 'date_cr_check', $this->boolean()->defaultValue(false)->after('date_cr'));
        $this->addColumn('flight', 'number_check', $this->boolean()->defaultValue(false)->after('number'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'order_check');
        $this->dropColumn('flight', 'date_cr_check');
        $this->dropColumn('flight', 'number_check');
    }
}
