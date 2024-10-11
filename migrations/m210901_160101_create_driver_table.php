<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210901_160101_create_driver_table`.
 */
class m210901_160101_create_driver_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('driver', [
            'id' => $this->primaryKey(),
            'data' => $this->string()->comment('Водитель ФИО'),
            'data_avto' => $this->string()->comment('Данные автомобиля'),
            'phone' => $this->string()->comment('Телефон'),
            'driver' => $this->binary()->comment('Водитель паспортные данные'),
        ]);

        

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        
        $this->dropTable('driver');
    }
}
