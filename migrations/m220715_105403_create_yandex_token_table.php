<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%yandex_token}}`.
 */
class m220715_105403_create_yandex_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%yandex_token}}', [
            'id' => $this->primaryKey(),
            'token' => $this->string()->comment('Токен'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%yandex_token}}');
    }
}
