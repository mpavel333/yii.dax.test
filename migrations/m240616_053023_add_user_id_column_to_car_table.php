<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%car}}`.
 */
class m240616_053023_add_user_id_column_to_car_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('car', 'user_id', $this->integer()->comment('Пользователь'));

        $this->createIndex(
            'idx-car-user_id',
            'car',
            'user_id'
        );
                        
        $this->addForeignKey(
            'fk-car-user_id',
            'car',
            'user_id',
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
        $this->dropForeignKey(
            'fk-car-user_id',
            'car'
        );

        $this->dropIndex(
            'idx-car-user_id',
            'car'
        );

        $this->dropColumn('car', 'user_id');
    }
}
