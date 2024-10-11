<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%car}}`.
 */
class m230617_124935_add_new_columns_to_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('car', 'client_id', $this->integer()->comment('Организация'));

        $this->addColumn('car', 'license_number', $this->string()->comment('Номер прав'));
        $this->addColumn('car', 'license_date_start', $this->date()->comment('Дата начала'));
        $this->addColumn('car', 'license_date_end', $this->date()->comment('Дата окончаний'));
    
        $this->createIndex(
            "idx-car-client_id",
            "car",
            "client_id"
        );

        $this->addForeignKey(
            "fk-car-client_id",
            "car",
            "client_id",
            "client",
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
            "fk-car-client_id",
            "car"
        );

        $this->dropIndex(
            "idx-car-client_id",
            "car"
        );

        $this->dropColumn('car', 'license_number');
        $this->dropColumn('car', 'license_date_start');
        $this->dropColumn('car', 'license_date_end');

        $this->dropColumn('car', 'client_id');
    }
}
