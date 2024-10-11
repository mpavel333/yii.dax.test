<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240702_144308_add_new_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'client_comment', $this->text()->comment('Комментарий из таблицы Клиенты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
