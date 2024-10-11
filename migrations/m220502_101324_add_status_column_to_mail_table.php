<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mail}}`.
 */
class m220502_101324_add_status_column_to_mail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mail', 'status', $this->integer()->comment('Статус'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mail', 'status');
    }
}
