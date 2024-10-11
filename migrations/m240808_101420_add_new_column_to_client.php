<?php

use yii\db\Migration;

/**
 * Class m240808_101420_add_new_column_to_client
 */
class m240808_101420_add_new_column_to_client extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'contract_orig', $this->boolean()->defaultValue(false)->comment('Договор Оригинал'));
        $this->addColumn('client', 'payment_terms', $this->string()->comment('Сроки оплаты'));

        $this->addColumn('client', 'is_lawyer', $this->boolean()->comment('Юрист'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240808_101420_add_new_column_to_client cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240808_101420_add_new_column_to_client cannot be reverted.\n";

        return false;
    }
    */
}
