<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240708_083232_add_new_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_lawyer', $this->boolean()->comment('Юрист'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
