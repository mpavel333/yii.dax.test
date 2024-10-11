<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%car}}`.
 */
class m240615_141719_add_kasko_columns_to_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('car', 'kasko_number', $this->string()->comment('Номер КАСКО'));
        $this->addColumn('car', 'kasko_date_start', $this->string()->comment('Дата начала'));
        $this->addColumn('car', 'kasko_date_end', $this->string()->comment('Дата окончания'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('car', 'kasko_number');
        $this->dropColumn('car', 'kasko_date_start');
        $this->dropColumn('car', 'kasko_date_end');
    }
}
