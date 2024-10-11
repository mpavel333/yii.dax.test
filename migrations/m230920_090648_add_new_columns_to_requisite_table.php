<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%requisite}}`.
 */
class m230920_090648_add_new_columns_to_requisite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('requisite', 'main_bank_name', $this->string()->comment('Основное наименование банка'));
        $this->addColumn('requisite', 'main_ogrn', $this->string()->comment('Основное огрн'));
        $this->addColumn('requisite', 'main_bic', $this->string()->comment('Основное бик'));
        $this->addColumn('requisite', 'main_kr', $this->string()->comment('Основное КР'));
        $this->addColumn('requisite', 'main_nomer_rascheta', $this->string()->comment('Основное Номер расчета'));

        $this->addColumn('requisite', 'add_bank_name', $this->string()->comment('Дополнительное наименование банка'));
        $this->addColumn('requisite', 'add_ogrn', $this->string()->comment('Дополнительное огрн'));
        $this->addColumn('requisite', 'add_bic', $this->string()->comment('Дополнительное бик'));
        $this->addColumn('requisite', 'add_kr', $this->string()->comment('Дополнительное КР'));
        $this->addColumn('requisite', 'add_nomer_rascheta', $this->string()->comment('Дополнительное Номер расчета'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('requisite', 'main_bank_name');
        $this->dropColumn('requisite', 'main_ogrn');
        $this->dropColumn('requisite', 'main_bic');
        $this->dropColumn('requisite', 'main_kr');
        $this->dropColumn('requisite', 'main_nomer_rascheta');
        $this->dropColumn('requisite', 'add_bank_name');
        $this->dropColumn('requisite', 'add_ogrn');
        $this->dropColumn('requisite', 'add_bic');
        $this->dropColumn('requisite', 'add_kr');
        $this->dropColumn('requisite', 'add_nomer_rascheta');
    }
}
