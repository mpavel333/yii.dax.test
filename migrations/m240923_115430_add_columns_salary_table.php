<?php

use yii\db\Migration;

/**
 * Class m240923_115430_add_columns_salary_table
 */
class m240923_115430_add_columns_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('salary', 'carrier_payment_type', $this->string()->comment('Тип оплаты перевозчика'));
        $this->addColumn('salary', 'customer_payment_type', $this->string()->comment('Тип оплаты заказчика'));
        
        $this->update('salary', [
            'carrier_payment_type' => 'по сканам ТТН  ТОРГ12 квитанция с почты РФ;по оригиналам+сканы ТТН;на выгрузке;по факту выгрузки;с момента доставки ОТТН и бух.док по адресу;по сканам',
            'customer_payment_type' => 'по сканам ТТН  ТОРГ12 квитанция с почты РФ;по оригиналам+сканы ТТН;на выгрузке;по факту выгрузки;с момента доставки ОТТН и бух.док по адресу'
            ], ['id' => 1]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240923_115430_add_columns_salary_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240923_115430_add_columns_salary_table cannot be reverted.\n";

        return false;
    }
    */
}
