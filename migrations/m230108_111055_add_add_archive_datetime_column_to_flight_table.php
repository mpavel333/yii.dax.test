<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m230108_111055_add_add_archive_datetime_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'success_datetime', $this->dateTime()->comment('Дата и время успеха'));
        $this->addColumn('flight', 'archive_datetime', $this->dateTime()->comment('Дата и время архивации'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'archive_datetime');
        $this->dropColumn('flight', 'success_datetime');
    }
}
