<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m220614_092842_add_flight_driver_upload_file_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_driver_upload_file', $this->boolean()->defaultValue(false)->comment('Загрузка файла перевозчика'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_driver_upload_file');
    }
}
