<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m220529_152837_add_flight_upload_file_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'flight_upload_file', $this->boolean()->defaultValue(false)->comment('Загрузка файлов'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'flight_upload_file');
    }
}
