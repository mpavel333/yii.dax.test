<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m231216_152620_add_encusrance_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'delta_form', $this->double()->comment('Дельта'));

        // Страхование
        $this->addColumn('flight', 'ensurance_number', $this->string()->comment('Номер'));
        $this->addColumn('flight', 'ensurance_date', $this->date()->comment('Дата'));
        $this->addColumn('flight', 'ensurance_organization', $this->string()->comment('Организация/Страхователь'));
        $this->addColumn('flight', 'ensurance_contract', $this->string()->comment('Ген. договор страхования грузов'));
        $this->addColumn('flight', 'ensurance_contract_where', $this->string()->comment('Где?'));
        $this->addColumn('flight', 'ensurance_currency', $this->string()->comment('Валюта'));
        $this->addColumn('flight', 'ensurance_client', $this->string()->comment('Контрагент'));
        $this->addColumn('flight', 'ensurance_profit_subject', $this->string()->comment('Выгодоприобретатель'));
        $this->addColumn('flight', 'ensurance_sum', $this->double()->comment('Страховая сумма'));
        $this->addColumn('flight', 'ensurance_prime', $this->double()->comment('Страховая премия'));
        $this->addColumn('flight', 'ensurance_transport_type', $this->text()->comment('Способ перевозки'));
        $this->addColumn('flight', 'ensurance_subject_from', $this->string()->comment('Регион отправки'));
        $this->addColumn('flight', 'ensurance_subject_to', $this->string()->comment('Регион прибытия'));
        $this->addColumn('flight', 'ensurance_country_from', $this->string()->comment('Страна отправки'));
        $this->addColumn('flight', 'ensurance_country_to', $this->string()->comment('Страна прибытия'));
        $this->addColumn('flight', 'ensurance_condition', $this->string()->comment('Условия страхования'));
        $this->addColumn('flight', 'ensurance_percent', $this->double()->comment('Процент'));
        $this->addColumn('flight', 'ensurance_ref', $this->boolean()->defaultValue(false)->comment('Реф. риски'));
        $this->addColumn('flight', 'ensurance_security', $this->boolean()->defaultValue(false)->comment('Охрана'));
        $this->addColumn('flight', 'ensurance_additional', $this->text()->comment('Доп. свед.'));
        $this->addColumn('flight', 'ensurance_order', $this->text()->comment('Заявка'));
        $this->addColumn('flight', 'ensurance_comment', $this->text()->comment('Комментарий'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('flight', 'delta_form');

        $this->dropColumn('flight', 'ensurance_number');
        $this->dropColumn('flight', 'ensurance_date');
        $this->dropColumn('flight', 'ensurance_organization');
        $this->dropColumn('flight', 'ensurance_contract');
        $this->dropColumn('flight', 'ensurance_contract_where');
        $this->dropColumn('flight', 'ensurance_currency');
        $this->dropColumn('flight', 'ensurance_client');
        $this->dropColumn('flight', 'ensurance_profit_subject');
        $this->dropColumn('flight', 'ensurance_sum');
        $this->dropColumn('flight', 'ensurance_prime');
        $this->dropColumn('flight', 'ensurance_transport_type');
        $this->dropColumn('flight', 'ensurance_subject_from');
        $this->dropColumn('flight', 'ensurance_subject_to');
        $this->dropColumn('flight', 'ensurance_country_from');
        $this->dropColumn('flight', 'ensurance_country_to');
        $this->dropColumn('flight', 'ensurance_condition');
        $this->dropColumn('flight', 'ensurance_percent');
        $this->dropColumn('flight', 'ensurance_ref');
        $this->dropColumn('flight', 'ensurance_security');
        $this->dropColumn('flight', 'ensurance_additional');
        $this->dropColumn('flight', 'ensurance_order');
        $this->dropColumn('flight', 'ensurance_comment');
    }
}
