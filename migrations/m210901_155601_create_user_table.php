<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m210901_155601_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
'login' => $this->string()->comment('Логин'),
'role_id' => $this->integer()->comment('Роль'),
'role' => $this->string()->comment('Должность'),
'name' => $this->string()->comment('ФИО'),
'phone' => $this->string()->comment('Телефон'),
'access' => $this->boolean()->comment('Доступ'),
'password_hash' => $this->string()->comment('Зашифрованный пароль'),
'created_at' => $this->datetime()->comment('Дата создания'),
'is_deletable' => $this->boolean()->comment('Можно удалить или нельзя'),
        ]);

        $this->createIndex(
            'idx-user-role_id',
            'user',
            'role_id'
        );

        $this->addForeignKey(
            'fk-user-role_id',
            'user',
            'role_id',
            'role',
            'id',
            'SET NULL'
        );

        $this->insert('user', [
            'login' => 'admin',
            'name' => 'Администратор',
            'role_id' => 1,
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'is_deletable' => false,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
