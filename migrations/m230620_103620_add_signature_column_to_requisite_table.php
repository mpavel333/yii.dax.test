<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%requisite}}`.
 */
class m230620_103620_add_signature_column_to_requisite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('requisite', 'signature', $this->string()->comment('Подпись'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('requisite', 'signature');
    }
}
