<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%car}}`.
 */
class m240819_092533_add_new_column_to_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('car', 'total_summ', $this->double()->comment('Общая сумма в мес. в руб.'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
