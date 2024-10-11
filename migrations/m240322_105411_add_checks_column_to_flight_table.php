<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m240322_105411_add_checks_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'checks1', $this->text()->comment('Сдача док-во 1'));
        $this->addColumn('flight', 'checks2', $this->text()->comment('Сдача док-во 2'));
        $this->addColumn('flight', 'checks3', $this->text()->comment('Сдача док-во 3'));

        $this->addColumn('role', 'docs1', $this->text()->comment('Документы 1'));
        $this->addColumn('role', 'docs2', $this->text()->comment('Документы 2'));
        $this->addColumn('role', 'docs3', $this->text()->comment('Документы 3'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('role', 'docs1');
        $this->dropColumn('role', 'docs2');
        $this->dropColumn('role', 'docs3');

        $this->dropColumn('flight', 'checks1');
        $this->dropColumn('flight', 'checks2');
        $this->dropColumn('flight', 'checks3');
    }
}
