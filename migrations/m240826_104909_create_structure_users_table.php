<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%structure_users}}`.
 */
class m240826_104909_create_structure_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%structure_users}}', [
            'id' => $this->primaryKey(),
            'structure_id' => $this->integer()->comment('Отдел'),
            'user_id' => $this->integer()->comment('Пользователь'),

            'login' => $this->string()->comment('Логин'),
            'name' => $this->string()->comment('ФИО'),
            'email' => $this->string()->comment('Почта'),
            'phone' => $this->string()->comment('Телефон'),
            'tabel' => $this->string()->comment('Табель'),
        ]);


        $this->createIndex(
            'idx-structure_users-structure_id',
            'structure_users',
            'structure_id'
        );
                        
        $this->addForeignKey(
            'fk-structure_users-structure_id',
            'structure_users',
            'structure_id',
            'structure',
            'id',
            'SET NULL'
        );

        $this->createIndex(
            'idx-structure_users-user_id',
            'structure_users',
            'user_id'
        );
                        
        $this->addForeignKey(
            'fk-structure_users-user_id',
            'structure_users',
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
        $this->dropTable('{{%structure_users}}');
    }
}
