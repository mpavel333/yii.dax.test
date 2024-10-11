<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m230920_092517_add_payment_bank_id_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'payment_bank_id', $this->integer()->comment('Банк для оплаты'));
    
        $this->createIndex(
            'idx-flight-payment_bank_id',
            'flight',
            'payment_bank_id'
        );
                        
        $this->addForeignKey(
            'fk-flight-payment_bank_id',
            'flight',
            'payment_bank_id',
            'bank',
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
            'fk-flight-payment_bank_id',
            'flight'
        );

        $this->dropIndex(
            'idx-flight-payment_bank_id',
            'flight'
        );

        $this->dropColumn('flight', 'payment_bank_id');
    }
}
