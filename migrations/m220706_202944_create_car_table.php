<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%car}}`.
 */
class m220706_202944_create_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%car}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'number' => $this->string()->comment('Гос. номер'),
            'status' => $this->integer()->comment('Статус'),
            'driver_id' => $this->integer()->comment('Водитель'),
            'mileage' => $this->double()->comment('Общий километраж'),
        ]);

        $this->createIndex(
            'idx-car-driver_id',
            'car',
            'driver_id'
        );
                        
        $this->addForeignKey(
            'fk-car-driver_id',
            'car',
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
            'fk-car-driver_id',
            'car'
        );

        $this->dropIndex(
            'idx-car-driver_id',
            'car'
        );

        $this->dropTable('{{%car}}');
    }
}
