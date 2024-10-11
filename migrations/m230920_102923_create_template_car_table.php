<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template_car}}`.
 */
class m230920_102923_create_template_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template_car}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'type' => $this->integer()->comment('Тип'),
            'text' =>  'LONGTEXT',
            'modifier' => $this->string()->comment('Модификатор'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template_car}}');
    }
}
