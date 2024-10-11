<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%structure}}`.
 */
class m240826_104851_create_structure_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%structure}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
        ]);

        $this->addColumn('role', 'structure', $this->boolean()->defaultValue(false)->comment('Структура компании'));
        $this->addColumn('role', 'structure_update', $this->boolean()->defaultValue(false)->comment('Структура компании Изменение'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%structure}}');
    }
}
