<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m230121_155105_add_new_columns_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'mail_host', $this->string()->comment('Хост почты'));
        $this->addColumn('user', 'mail_pass', $this->string()->comment('Пароль от почты'));
        $this->addColumn('user', 'mail_port', $this->string()->comment('Порт почты'));
        $this->addColumn('user', 'mail_encryption', $this->string()->comment('Протокол почты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'mail_host');
        $this->dropColumn('user', 'mail_pass');
        $this->dropColumn('user', 'mail_port');
        $this->dropColumn('user', 'mail_encryption');
    }
}
