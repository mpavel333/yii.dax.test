<?php

use yii\db\Migration;

/**
 * Class m220407_150955_add_name_case_column_to_client_and_requisite_tables
 */
class m220407_150955_add_name_case_column_to_client_and_requisite_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'name_case', $this->string()->comment('Наименование в падеже'));
        $this->addColumn('requisite', 'name_case', $this->string()->comment('Наименование в падеже'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('client', 'name_case');
        $this->dropColumn('requisite', 'name_case');
    }
}
