<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%metal}}`.
 */
class m231130_094203_add_new_columns_to_metal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Роли
        $this->addColumn('role', 'metal_create', $this->boolean()->defaultValue(false)->comment('Металлика Добавление'));
        $this->addColumn('role', 'metal_update', $this->boolean()->defaultValue(false)->comment('Металлика Изменение'));
        $this->addColumn('role', 'metal_view', $this->boolean()->defaultValue(false)->comment('Металлика Просмотр'));
        $this->addColumn('role', 'metal_view_all', $this->boolean()->defaultValue(false)->comment('Металлика Просмотр всех'));
        $this->addColumn('role', 'metal_delete', $this->boolean()->defaultValue(false)->comment('Металлика Удалить'));
        $this->addColumn('role', 'metal_disallow_fields', $this->text()->comment('Металлика Исключить поля'));

        // Связь с рейсом
        $this->addColumn('metal', 'flight_id', $this->integer()->comment('Рейс'));
        $this->createIndex(
            'idx-metal-flight_id',
            'metal',
            'flight_id'
        );          
        $this->addForeignKey(
            'fk-metal-flight_id',
            'metal',
            'flight_id',
            'flight',
            'id',
            'SET NULL'
        );

        // Отправитель
        $this->addColumn('metal', 'sender_id', $this->integer()->comment('Отправитель'));
        $this->createIndex(
            'idx-metal-sender_id',
            'metal',
            'sender_id'
        );          
        $this->addForeignKey(
            'fk-metal-sender_id',
            'metal',
            'sender_id',
            'client',
            'id',
            'SET NULL'
        );

        // Оплата отправителю
        $this->addColumn('metal', 'sender_payment', $this->double()->comment('Оплата отправителю'));
        $this->addColumn('metal', 'sender_payment_form', $this->string()->comment('Форма оплаты'));
        $this->addColumn('metal', 'sender_payment_type', $this->string()->comment('Тип оплаты'));
        $this->addColumn('metal', 'sender_days', $this->string()->comment('Кол-во дней'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Оплата отправителю
        $this->dropColumn('metal', 'sender_payment');
        $this->dropColumn('metal', 'sender_payment_form');
        $this->dropColumn('metal', 'sender_payment_type');
        $this->dropColumn('metal', 'sender_days');

        // Отправитель
        $this->dropForeignKey(
            'fk-metal-sender_id',
            'metal'
        );
        $this->dropIndex(
            'idx-metal-sender_id',
            'metal'
        );
        $this->dropColumn('metal', 'sender_id');

        // Связь с рейсом
        $this->dropForeignKey(
            'fk-metal-flight_id',
            'metal'
        );
        $this->dropIndex(
            'idx-metal-flight_id',
            'metal'
        );
        $this->dropColumn('metal', 'flight_id');

        // Роли
        $this->dropColumn('role', 'metal_create');
        $this->dropColumn('role', 'metal_update');
        $this->dropColumn('role', 'metal_view');
        $this->dropColumn('role', 'metal_view_all');
        $this->dropColumn('role', 'metal_delete');
        $this->dropColumn('role', 'metal_disallow_fields');
    }
}
