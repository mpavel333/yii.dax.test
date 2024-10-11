<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240112_110513_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'flights_count', $this->integer()->comment('Кол-во рейсов'));
        $this->addColumn('flight', 'bill_type_prepayed', $this->string()->comment('Тип счёта (Авансовый)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'flights_count');
        $this->dropColumn('flight', 'bill_type_prepayed');
    }
}
