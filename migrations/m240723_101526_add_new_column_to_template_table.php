<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%template}}`.
 */
class m240723_101526_add_new_column_to_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('template', 'ord', $this->integer()->comment('Сортировка'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
