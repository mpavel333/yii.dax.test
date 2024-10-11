<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m240615_141333_create_ticket_table`.
 */
class m240615_141333_create_ticket_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ticket', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('Пользователь'),
            'create_at' => $this->datetime()->comment('Дата и время'),
            'subject' => $this->string()->comment('Заголовок'),
            'status' => $this->double()->defaultValue(0)->comment('Статус'),
            'user_service_id' => $this->integer()->comment('Пользователь службы поддержки'),
        ]);

        $this->createIndex(
            'idx-ticket-user_id',
            'ticket',
            'user_id'
        );
                        
        $this->addForeignKey(
            'fk-ticket-user_id',
            'ticket',
            'user_id',
            'user',
            'id',
            'SET NULL'
        );
                        $this->createIndex(
            'idx-ticket-user_service_id',
            'ticket',
            'user_service_id'
        );
                        
        $this->addForeignKey(
            'fk-ticket-user_service_id',
            'ticket',
            'user_service_id',
            'user',
            'id',
            'SET NULL'
        );
                        


            $this->addColumn('role', 'ticket_create', $this->boolean()->defaultValue(false)->comment('Техническая поддержка Добавление'));
            $this->addColumn('role', 'ticket_update', $this->boolean()->defaultValue(false)->comment('Техническая поддержка Изменение'));
            $this->addColumn('role', 'ticket_view', $this->boolean()->defaultValue(false)->comment('Техническая поддержка Просмотр'));
            $this->addColumn('role', 'ticket_view_all', $this->boolean()->defaultValue(false)->comment('Техническая поддержка Просмотр всех'));
            $this->addColumn('role', 'ticket_index', $this->boolean()->defaultValue(false)->comment('Техническая поддержка Просмотр таблицы'));
            $this->addColumn('role', 'ticket_delete', $this->boolean()->defaultValue(false)->comment('Техническая поддержка Удалить'));
            $this->addColumn('role', 'ticket_disallow_fields', $this->text()->comment('Техническая поддержка Исключить поля'));

        \app\models\Role::updateAll([
            'ticket_create' => true,
            'ticket_update' => true,
            'ticket_view' => true,
            'ticket_view_all' => true,
            'ticket_index' => true,
            'ticket_delete' => true,
            'ticket_disallow_fields' => true,
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('role', 'ticket_create');
        $this->dropColumn('role', 'ticket_update');
        $this->dropColumn('role', 'ticket_view');
        $this->dropColumn('role', 'ticket_view_all');
        $this->dropColumn('role', 'ticket_index');
        $this->dropColumn('role', 'ticket_delete');
        $this->dropColumn('role', 'ticket_disallow_fields');

        $this->dropForeignKey(
            'fk-ticket-user_id',
            'ticket'
        );
                        
        $this->dropIndex(
            'idx-ticket-user_id',
            'ticket'
        );
                        
                        $this->dropForeignKey(
            'fk-ticket-user_service_id',
            'ticket'
        );
                        
        $this->dropIndex(
            'idx-ticket-user_service_id',
            'ticket'
        );
                        
                        
        $this->dropTable('ticket');
    }
}
