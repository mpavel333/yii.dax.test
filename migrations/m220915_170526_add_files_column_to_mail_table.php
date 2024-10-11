<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mail}}`.
 */
class m220915_170526_add_files_column_to_mail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mail', 'files', $this->text()->comment('Файлы'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mail', 'files');
    }
}
