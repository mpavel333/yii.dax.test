<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%flight_history}}`.
 */
class m240921_060620_create_flight_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%flight_history}}', [
            'id' => $this->primaryKey(),
            'flight_id' => $this->integer()->comment('Рейсы'),
            'data' => $this->binary()->comment('Данные'),
            'user_id' => $this->integer()->comment('Пользователь'),
            'create_at' => $this->dateTime()->comment('Дата и время'), 
        ]);

        $this->createIndex(
            'idx-flight_history-flight_id',
            'flight_history',
            'flight_id'
        );
                        
        $this->addForeignKey(
            'fk-flight_history-flight_id',
            'flight_history',
            'flight_id',
            'flight',
            'id',
            'SET NULL'
        );

        $this->createIndex(
            'idx-flight_history-user_id',
            'flight_history',
            'user_id'
        );
                        
        $this->addForeignKey(
            'fk-flight_history-user_id',
            'flight_history',
            'user_id',
            'user',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-flight_history-user_id',
            'flight_history'
        );

        $this->dropIndex(
            'idx-flight_history-user_id',
            'flight_history'
        );

        $this->dropForeignKey(
            'fk-flight_history-flight_id',
            'flight_history'
        );

        $this->dropIndex(
            'idx-flight_history-flight_id',
            'flight_history'
        );

        $this->dropTable('{{%flight_history}}');
    }
}
