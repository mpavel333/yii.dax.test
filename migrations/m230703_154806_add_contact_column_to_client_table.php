<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%client}}`.
 */
class m230703_154806_add_contact_column_to_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'contact', $this->string()->comment('Контакт и телефон'));
        $this->addColumn('flight', 'name_price', $this->double()->comment('Стоимость груза'));
        $this->addColumn('flight', 'place_count', $this->integer()->comment('Количество мест'));
        $this->addColumn('requisite', 'card', $this->string()->comment('Номер карты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('client', 'contact');
        $this->dropColumn('flight', 'name_price');
        $this->dropColumn('flight', 'place_count');
        $this->dropColumn('requisite', 'card');
    }
}
