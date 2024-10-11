<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m221119_080113_add_info_from_client_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'info_from_client', $this->string()->comment('Информация от Заказчика'));
        $this->addColumn('flight', 'letter_info', $this->string()->comment('Информация письма'));
        $this->addColumn('flight', 'letter_info_driver', $this->string()->comment('Информация письма водителя'));
        $this->addColumn('flight', 'is_register_letter', $this->boolean()->defaultValue(false)->comment('Регистрация почты'));

        $this->addColumn('mail', 'information', $this->string()->comment('Информация письма'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'info_from_client');
        $this->dropColumn('flight', 'letter_info');
        $this->dropColumn('flight', 'letter_info_driver');
        $this->dropColumn('flight', 'is_register_letter');

        $this->dropColumn('mail', 'information');
    }
}
