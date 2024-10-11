<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m230222_082459_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->comment('Клиент'),
            'type' => $this->integer()->comment('Тип'),
            'amount' => $this->double()->comment('Сумма'),
            'payment' => $this->string()->comment('Плательщик'),
            'payment_inn' => $this->string()->comment('ИНН плательщика'),
            'receiver' => $this->string()->comment('Получатель'),
            'receiver_inn' => $this->string()->comment('ИНН получателя'),
            'date' => $this->date()->comment('Дата'),
        ]);

        $this->createIndex(
            "idx-payment-client_id",
            "payment",
            "client_id"
        );

        $this->addForeignKey(
            "fk-payment-client_id",
            "payment",
            "client_id",
            "client",
            "id",
            "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            "fk-payment-client_id",
            "payment"
        );

        $this->dropIndex(
            "idx-payment-client_id",
            "payment"
        );

        $this->dropTable('{{%payment}}');
    }
}
