<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240724_090322_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'is_recoil_payment_date', $this->dateTime()->after('is_recoil_payment')->comment('Дата баллы'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
