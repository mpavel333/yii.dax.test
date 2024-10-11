<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m220804_110938_add_bank_id_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'bank_id', $this->integer()->comment('Банк'));

        $this->createIndex(
            "idx-flight-bank_id",
            "flight",
            "bank_id"
        );

        $this->addForeignKey(
            "fk-flight-bank_id",
            "flight",
            "bank_id",
            "bank",
            "id",
            "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            "fk-flight-bank_id",
            "flight"
        );

        $this->dropIndex(
            "idx-flight-bank_id",
            "flight"
        );

        $this->dropColumn('flight', 'bank_id');
    }
}
