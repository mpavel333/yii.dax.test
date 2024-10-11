<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m240615_135052_create_message_table`.
 */
class m240615_135052_create_message_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('message', [
            'id' => $this->primaryKey(),
            'flight_id' => $this->integer()->comment('Заявка'),
            'text' => $this->binary(8294967295)->comment('Текст'),
            'user_id' => $this->integer()->comment('Пользователь'),
            'create_at' => $this->datetime()->comment('Дата и время'),
            'is_read' => $this->boolean()->comment('Прочитано'),
            'user_to_id' => $this->integer()->comment('Кому'),
        ]);

        $this->createIndex(
            'idx-message-flight_id',
            'message',
            'flight_id'
        );
                        
        $this->addForeignKey(
            'fk-message-flight_id',
            'message',
            'flight_id',
            'flight',
            'id',
            'SET NULL'
        );
                        $this->createIndex(
            'idx-message-user_id',
            'message',
            'user_id'
        );
                        
        $this->addForeignKey(
            'fk-message-user_id',
            'message',
            'user_id',
            'user',
            'id',
            'SET NULL'
        );
                        $this->createIndex(
            'idx-message-user_to_id',
            'message',
            'user_to_id'
        );
                        
        $this->addForeignKey(
            'fk-message-user_to_id',
            'message',
            'user_to_id',
            'user',
            'id',
            'SET NULL'
        );
                        


        $this->addColumn('role', 'message_create', $this->boolean()->defaultValue(false)->comment('Сообщения Добавление'));
        $this->addColumn('role', 'message_update', $this->boolean()->defaultValue(false)->comment('Сообщения Изменение'));
        $this->addColumn('role', 'message_view', $this->boolean()->defaultValue(false)->comment('Сообщения Просмотр'));
        $this->addColumn('role', 'message_view_all', $this->boolean()->defaultValue(false)->comment('Сообщения Просмотр всех'));
        $this->addColumn('role', 'message_index', $this->boolean()->defaultValue(false)->comment('Сообщения Просмотр таблицы'));
        $this->addColumn('role', 'message_delete', $this->boolean()->defaultValue(false)->comment('Сообщения Удалить'));
        $this->addColumn('role', 'message_disallow_fields', $this->text()->comment('Сообщения Исключить поля'));


        \app\models\Role::updateAll([
            'message_create' => true,
            'message_update' => true,
            'message_view' => true,
            'message_view_all' => true,
            'message_index' => true,
            'message_delete' => true,
            'message_disallow_fields' => true,
        ]);

        $this->addColumn('role', 'flight_chat', $this->text()->comment('Чат'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('role', 'flight_chat');
        
        $this->dropColumn('role', 'message_create');
        $this->dropColumn('role', 'message_update');
        $this->dropColumn('role', 'message_view');
        $this->dropColumn('role', 'message_view_all');
        $this->dropColumn('role', 'message_index');
        $this->dropColumn('role', 'message_delete');
        $this->dropColumn('role', 'message_disallow_fields');

        $this->dropForeignKey(
            'fk-message-flight_id',
            'message'
        );
                        
        $this->dropIndex(
            'idx-message-flight_id',
            'message'
        );
                        
        $this->dropForeignKey(
            'fk-message-user_id',
            'message'
        );
                        
        $this->dropIndex(
            'idx-message-user_id',
            'message'
        );
                        
        $this->dropForeignKey(
            'fk-message-user_to_id',
            'message'
        );
                        
        $this->dropIndex(
            'idx-message-user_to_id',
            'message'
        );
                        
                        
        $this->dropTable('message');
    }
}
