<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%login_connect}}`.
 */
class m230212_153352_create_login_connect_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('login_connect', [
            'id' => $this->primaryKey(),
            'ip_address' => $this->string()->comment('IP адрес'),
            'status' => $this->string()->comment('Статус'),
            'code' => $this->string()->comment('Код'),
            'create_at' => $this->datetime()->comment('Дата и время'),
            'login' => $this->string()->comment('Логин'),
            'password' => $this->string()->comment('Пароль'),
        ]);

        

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        
        $this->dropTable('login_connect');
    }
}
