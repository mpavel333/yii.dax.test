<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m230920_085739_add_information_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'information_file_path', $this->string()->comment('Файл информации'));
        $this->addColumn('flight', 'information', $this->binary()->comment('Информация'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'information_file_path');
        $this->dropColumn('flight', 'information');
    }
}
