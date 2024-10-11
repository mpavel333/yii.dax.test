<?php

use yii\db\Migration;

/**
 * Class m220429_093704_alter_flight_table
 */
class m220429_093704_alter_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('flight', 'number', $this->string()->comment('Счёт'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
