<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240719_105851_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_metal', $this->boolean()->defaultValue(false)->comment('Металлика'));
        $this->addColumn('flight', 'file_client', $this->text()->comment('Файлы покупателя'));
        $this->addColumn('flight', 'client_id', $this->integer()->comment('Покупатель'));

        $this->createIndex(
            'idx-flight-client_id',
            'flight',
            'client_id'
        );
                        
        $this->addForeignKey(
            'fk-flight-client_id',
            'flight',
            'client_id',
            'client',
            'id',
            'SET NULL'
        );

        $this->addColumn('flight', 'client_payment', $this->double()->comment('Оплата покупателя'));
        $this->addColumn('flight', 'client_payment_prepayment', $this->double()->comment('Оплата покупателя'));
        $this->addColumn('flight', 'client_payment_form', $this->string()->comment('Форма оплаты покупателя'));
        $this->addColumn('flight', 'client_payment_type', $this->string()->comment('Тип оплаты покупателя'));
        $this->addColumn('flight', 'client_payment_days', $this->integer()->comment('Дни оплаты покупателя'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-flight-client_id',
            'flight'
        );

        $this->dropIndex(
            'idx-flight-client_id',
            'flight'
        );

        $this->dropColumn('flight', 'is_metal');
        $this->dropColumn('flight', 'file_client');
        $this->dropColumn('flight', 'client_id');   
    
    
        $this->dropColumn('flight', 'client_payment');
        $this->dropColumn('flight', 'client_payment_prepayment');
        $this->dropColumn('flight', 'client_payment_form');
        $this->dropColumn('flight', 'client_payment_type');
        $this->dropColumn('flight', 'client_payment_days');
    }
}
