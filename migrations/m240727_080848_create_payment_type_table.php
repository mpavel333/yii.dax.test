<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_type}}`.
 */
class m240727_080848_create_payment_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_type_client}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
        ]);

        $this->createTable('{{%payment_type_driver}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment_type_client}}');
        $this->dropTable('{{%payment_type_driver}}');
    }
}
