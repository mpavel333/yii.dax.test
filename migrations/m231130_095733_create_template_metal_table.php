<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template_metal}}`.
 */
class m231130_095733_create_template_metal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template_metal}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'type' => $this->integer()->comment('Тип'),
            'text' => 'LONGTEXT',
            'modifier' => $this->string()->comment('Модификатор'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template_metal}}');
    }
}
