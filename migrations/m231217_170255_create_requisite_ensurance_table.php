<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%requisite_ensurance}}`.
 */
class m231217_170255_create_requisite_ensurance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%requisite_ensurance}}', [
            'id' => $this->primaryKey(),
            'requisite_id' => $this->integer()->comment('Реквизиты'),
            'name' => $this->string()->comment('Название'),
            'contract' => $this->string()->comment('Договор'),
            'condition' => $this->string()->comment('Условие'),
            'percent' => $this->double()->comment('Процент'),
        ]);

        $this->createIndex(
            'idx-requisite_ensurance-requisite_id',
            'requisite_ensurance',
            'requisite_id'
        );

        $this->addForeignKey(
            'fk-requisite_ensurance-requisite_id',
            'requisite_ensurance',
            'requisite_id',
            'requisite',
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
            'fk-requisite_ensurance-requisite_id',
            'requisite_ensurance'
        );

        $this->dropIndex(
            'idx-requisite_ensurance-requisite_id',
            'requisite_ensurance'
        );

        $this->dropTable('{{%requisite_ensurance}}');
    }
}
