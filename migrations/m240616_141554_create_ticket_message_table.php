<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m240616_141554_create_ticket_message_table`.
 */
class m240616_141554_create_ticket_message_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ticket_message', [
            'id' => $this->primaryKey(),
            'text' => $this->string()->comment('Текст'),
            'application' => $this->binary(8294967295)->comment('Приложение'),
            'user_id' => $this->integer()->comment('Пользователь'),
            'create_at' => $this->datetime()->comment('Дата и время'),
            'is_read' => $this->boolean()->comment('Прочитан'),
            'ticket_id' => $this->integer()->comment('Тикет'),
        ]);

        $this->createIndex(
            'idx-ticket_message-user_id',
            'ticket_message',
            'user_id'
        );
                        
        $this->addForeignKey(
            'fk-ticket_message-user_id',
            'ticket_message',
            'user_id',
            'user',
            'id',
            'SET NULL'
        );
        $this->createIndex(
            'idx-ticket_message-ticket_id',
            'ticket_message',
            'ticket_id'
        );
                        
        $this->addForeignKey(
            'fk-ticket_message-ticket_id',
            'ticket_message',
            'ticket_id',
            'ticket',
            'id',
            'SET NULL'
        );
                        

        $this->addColumn('role', 'ticket_message_create', $this->boolean()->defaultValue(false)->comment('Сообщения технической поддержки Добавление'));
        $this->addColumn('role', 'ticket_message_update', $this->boolean()->defaultValue(false)->comment('Сообщения технической поддержки Изменение'));
        $this->addColumn('role', 'ticket_message_view', $this->boolean()->defaultValue(false)->comment('Сообщения технической поддержки Просмотр'));
        $this->addColumn('role', 'ticket_message_view_all', $this->boolean()->defaultValue(false)->comment('Сообщения технической поддержки Просмотр всех'));
        $this->addColumn('role', 'ticket_message_index', $this->boolean()->defaultValue(false)->comment('Сообщения технической поддержки Просмотр таблицы'));
        $this->addColumn('role', 'ticket_message_delete', $this->boolean()->defaultValue(false)->comment('Сообщения технической поддержки Удалить'));
        $this->addColumn('role', 'ticket_message_disallow_fields', $this->text()->comment('Сообщения технической поддержки Исключить поля'));


        \app\models\Role::updateAll([
            'ticket_message_create' => true, 
            'ticket_message_update' => true, 
            'ticket_message_view' => true, 
            'ticket_message_view_all' => true, 
            'ticket_message_index' => true, 
            'ticket_message_delete' => true, 
            'ticket_message_disallow_fields' => true, 
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        $this->dropForeignKey(
            'fk-ticket_message-user_id',
            'ticket_message'
        );
                        
        $this->dropIndex(
            'idx-ticket_message-user_id',
            'ticket_message'
        );
                        
                        $this->dropForeignKey(
            'fk-ticket_message-ticket_id',
            'ticket_message'
        );
                        
        $this->dropIndex(
            'idx-ticket_message-ticket_id',
            'ticket_message'
        );
                        
                        
        $this->dropTable('ticket_message');
    }
}
