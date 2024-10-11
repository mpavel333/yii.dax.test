<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%template}}`.
 */
class m210929_011409_add_type_column_to_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('template', 'modifier', $this->string()->comment('Тип'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('template', 'modifier');
    }
}
