<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%car_to}}`.
 */
class m220715_094047_create_car_to_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%car_to}}', [
            'id' => $this->primaryKey(),
            'car_id' => $this->integer()->comment('Авто'),
            'name' => $this->string()->comment('Наименование'),
            'date' => $this->date()->comment('Дата'),
            'mileage' => $this->double()->comment('Километраж'),
            'info' => $this->string()->comment('Информация'),
            'driver_id' => $this->integer()->comment('Водитель'),
        ]);

        $this->createIndex(
            'idx-car_to-car_id',
            'car_to',
            'car_id'
        );
                        
        $this->addForeignKey(
            'fk-car_to-car_id',
            'car_to',
            'car_id',
            'car',
            'id',
            'SET NULL'
        );

        $this->createIndex(
            'idx-car_to-driver_id',
            'car_to',
            'driver_id'
        );
                        
        $this->addForeignKey(
            'fk-car_to-driver_id',
            'car_to',
            'driver_id',
            'driver',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-car_to-driver_id',
            'car_to'
        );

        $this->dropIndex(
            'idx-car_to-driver_id',
            'car_to'
        );

        $this->dropForeignKey(
            'fk-car_to-car_id',
            'car_to'
        );

        $this->dropIndex(
            'idx-car_to-car_id',
            'car_to'
        );

        $this->dropTable('{{%car_to}}');
    }
}
