<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m240614_142203_add_new_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'date_of_birth', $this->date()->comment('Дата рождения'));
        $this->addColumn('user', 'dolzhnost', $this->string()->comment('Должность'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
