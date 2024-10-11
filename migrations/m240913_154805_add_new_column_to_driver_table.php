<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%driver}}`.
 */
class m240913_154805_add_new_column_to_driver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('driver', 'snils', $this->string()->comment('СНИЛС'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
