<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%driver}}`.
 */
class m220324_193910_add_file_column_to_driver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('driver', 'file', $this->text()->comment('Файлы'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('driver', 'file');
    }
}
