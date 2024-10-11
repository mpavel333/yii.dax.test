<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%call}}`.
 */
class m230730_102146_create_call_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%call}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string()->comment('Телефон'),
            'phone1' => $this->string()->comment('Телефон1'),
            'phone2' => $this->string()->comment('Телефон2'),
            'client_id' => $this->integer()->comment('Клиент'),
            'status' => $this->integer()->comment('Статус'),
            'inn' => $this->string()->comment('ИНН'),
            'post' => $this->string()->comment('Почта'),
            'site' => $this->string()->comment('Сайт'),
            'industry' => $this->string()->comment('Отрасль'),
            'region' => $this->string()->comment('Область'),
            'city' => $this->string()->comment('Город'),
            'contact_name' => $this->string()->comment('Контакт'),
            'timezone' => $this->string()->comment('Часовой пояс'),
            'result' => $this->string()->comment('Результат'),
            'result_text' => $this->string()->comment('Текст результата'),
            'files' => $this->binary()->comment('Файлы'),
            'user_id' => $this->integer()->comment('Пользователь'),
            'create_at' => $this->dateTime()->comment('Дата и время'),
        ]);


        $this->createIndex(
            'idx-call-client_id',
            'call',
            'client_id'
        );

        $this->addForeignKey(
            'fk-call-client_id',
            'call',
            'client_id',
            'client',
            'id',
            'SET NULL'
        );

        $this->createIndex(
            'idx-call-user_id',
            'call',
            'user_id'
        );

        $this->addForeignKey(
            'fk-call-user_id',
            'call',
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
            'fk-call-client_id',
            'call'
        );

        $this->dropIndex(
            'idx-call-client_id',
            'call'
        );

        $this->dropForeignKey(
            'fk-call-user_id',
            'call'
        );

        $this->dropIndex(
            'idx-call-user_id',
            'call'
        );

        $this->dropTable('{{%call}}');
    }
}
