<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%requisite}}`.
 */
class m240516_151213_add_new_columns_to_requisite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('requisite', 'contacts1', $this->string()->comment('Контакты 1'));
        $this->addColumn('requisite', 'contacts2', $this->string()->comment('Контакты 2'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('requisite', 'contacts1');
        $this->dropColumn('requisite', 'contacts1');
    }
}
