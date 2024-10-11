<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mail}}`.
 */
class m240613_104908_add_new_column_to_mail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mail', 'upd', $this->string()->comment('УПД'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
