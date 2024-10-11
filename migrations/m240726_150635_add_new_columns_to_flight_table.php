<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240726_150635_add_new_columns_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'date_cr_prepayed', $this->date()->after('date_cr')->comment('Дата УПД\Счет аванс(предоплата)'));
        $this->addColumn('flight', 'number_prepayed', $this->double()->after('number')->comment('Счёт аванс(предоплата)'));
        $this->addColumn('flight', 'upd_prepayed', $this->string()->after('upd')->comment('УПД аванс(предоплата)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
