<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%car_to}}`.
 */
class m240615_150141_add_user_response_column_to_car_to_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('car_to', 'response_user', $this->string()->comment('Ответственный'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('car_to', 'response_user');
    }
}
