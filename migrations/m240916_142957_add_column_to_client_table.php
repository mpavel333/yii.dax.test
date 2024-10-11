<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%client}}`.
 */
class m240916_142957_add_column_to_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'payment_type', $this->string()->after('payment_terms')->comment('Тип оплаты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
