<?php

use yii\db\Migration;

/**
 * Class m240625_082037_add_new_column_to_car_to
 */
class m240625_082037_add_new_column_to_car_to extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('car_to', 'response_user_id', $this->integer()->comment('Ответственный ID'));

        $this->createIndex(
            'idx-car_to-response_user_id',
            'car_to',
            'response_user_id'
        );
                        
        $this->addForeignKey(
            'fk-car-response_user_id',
            'car_to',
            'response_user_id',
            'user',
            'id',
            'SET NULL'
        );


        $this->addColumn('car_to', 'close_user_id', $this->integer()->comment('Закрыл ID'));

        $this->createIndex(
            'idx-car_to-close_user_id',
            'car_to',
            'close_user_id'
        );
                        
        $this->addForeignKey(
            'fk-car-close_user_id',
            'car_to',
            'close_user_id',
            'user',
            'id',
            'SET NULL'
        );



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240625_082037_add_new_column_to_car_to cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240625_082037_add_new_column_to_car_to cannot be reverted.\n";

        return false;
    }
    */
}
