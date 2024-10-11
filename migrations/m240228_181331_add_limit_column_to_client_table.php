<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%client}}`.
 */
class m240228_181331_add_limit_column_to_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'limit', $this->string()->comment('Лимит'));
        $this->addColumn('salary', 'limit', $this->double()->comment('Лимит'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('client', 'limit');
        $this->dropColumn('salary', 'limit');
    }
}
