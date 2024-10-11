<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m220529_160918_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'shipping_date_2', $this->date());
        $this->addColumn('flight', 'date_out4_2', $this->date());
        $this->addColumn('flight', 'date_out2_2', $this->date());
        $this->addColumn('flight', 'date_out5_2', $this->date());
        $this->addColumn('flight', 'date_out3_2', $this->date());
        $this->addColumn('flight', 'date_out6_2', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'shipping_date_2');
        $this->dropColumn('flight', 'date_out4_2');
        $this->dropColumn('flight', 'date_out2_2');
        $this->dropColumn('flight', 'date_out5_2');
        $this->dropColumn('flight', 'date_out3_2');
        $this->dropColumn('flight', 'date_out6_2');
    }
}
