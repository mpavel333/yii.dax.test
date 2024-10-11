<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240920_154316_add_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'history', $this->binary()->comment('История изменений'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
