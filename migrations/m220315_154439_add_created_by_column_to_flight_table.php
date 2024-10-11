<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flight}}`.
 */
class m220315_154439_add_created_by_column_to_flight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flight', 'created_by', $this->integer()->comment('Создал'));

        $this->createIndex(
            "idx-flight-created_by",
            "flight",
            "created_by"
        );

        $this->addForeignKey(
            "fk-flight-created_by",
            "flight",
            "created_by",
            "user",
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
            "fk-flight-created_by",
            "flight"
        );

        $this->dropIndex(
            "idx-flight-created_by",
            "flight"
        );

        $this->dropColumn('flight', 'created_by');
    }
}
