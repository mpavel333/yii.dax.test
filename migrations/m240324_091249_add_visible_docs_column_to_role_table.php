<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%role}}`.
 */
class m240324_091249_add_visible_docs_column_to_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('role', 'docs_readonly', $this->text()->comment('Только для чтения'));
        $this->addColumn('role', 'docs1_readonly', $this->text()->comment('Только для чтения'));
        $this->addColumn('role', 'docs2_readonly', $this->text()->comment('Только для чтения'));
        $this->addColumn('role', 'docs3_readonly', $this->text()->comment('Только для чтения'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'docs_readonly');
        $this->dropColumn('role', 'docs1_readonly');
        $this->dropColumn('role', 'docs2_readonly');
        $this->dropColumn('role', 'docs3_readonly');
    }
}
