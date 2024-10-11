<?php

use yii\db\Migration;

/**
 * Class m240215_171109_creatae_template_client_table
 */
class m240215_171109_creatae_template_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template_client}}', [
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
        $this->dropTable('{{%template_client}}');
    }
}
