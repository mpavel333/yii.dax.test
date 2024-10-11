<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m221112_082428_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'act', $this->string()->comment('Акт'));
        $this->addColumn('flight', 'act_date', $this->date()->comment('Дата'));
        $this->addColumn('flight', 'track_number', $this->string()->comment('Трек номер'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'act');
        $this->dropColumn('flight', 'act');
        $this->dropColumn('flight', 'track_number');
    }
}
