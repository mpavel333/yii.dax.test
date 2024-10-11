<?php

use yii\db\Migration;

/**
 * Class m230419_114221_add_daks_balance_to_user_and_flight_tables
 */
class m230419_114221_add_daks_balance_to_user_and_flight_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'daks_balance', $this->double()->defaultValue(0)->comment('Даксы'));
        $this->addColumn('user', 'daks_balance', $this->double()->defaultValue(0)->comment('Даксы'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'daks_balance');
        $this->dropColumn('flight', 'daks_balance');
    }
}
