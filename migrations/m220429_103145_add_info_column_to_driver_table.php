<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%driver}}`.
 */
class m220429_103145_add_info_column_to_driver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('driver', 'info', $this->string()->comment('Информация о водителе'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('driver', 'info');
    }
}
