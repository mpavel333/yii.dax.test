<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%call_row}}`.
 */
class m230805_142018_create_call_row_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%call_row}}', [
            'id' => $this->primaryKey(),
            'call_id' => $this->integer()->comment('Звонок'),
            'text' => $this->string()->comment('Текст'),
            'datetime' => $this->dateTime()->comment('Дата и время'),
        ]);

        $this->createIndex(
            "idx-call_row-call_id",
            "call_row",
            "call_id"
        );

        $this->addForeignKey(
            "fk-call_row-call_id",
            "call_row",
            "call_id",
            "call",
            "id",
            "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            "fk-call_row-call_id",
            "call_row"
        );

        $this->dropIndex(
            "idx-call_row-call_id",
            "call_row"
        );

        $this->dropTable('{{%call_row}}');
    }
}
