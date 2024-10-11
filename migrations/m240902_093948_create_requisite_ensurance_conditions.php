<?php

use yii\db\Migration;

/**
 * Class m240902_093948_create_requisite_ensurance_conditions
 */
class m240902_093948_create_requisite_ensurance_conditions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->createTable('{{%requisite_ensurance_conditions}}', [
            'id' => $this->primaryKey(),
            
            'requisite_ensurance_id' => $this->integer()->comment('ID страховки'),
            
            'condition' => $this->string()->comment('Условие'),
            'percent' => $this->double()->comment('Процент'),
        ]);
        
         
        $this->createIndex(
            'idx-rec-id',
            'requisite_ensurance_conditions',
            'id'
        );
                        
        $this->addForeignKey(
            'fk-rec-id',
            'requisite_ensurance_conditions',
            'requisite_ensurance_id',
                
            'requisite_ensurance',
            'id',
            'SET NULL'
        );        

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240902_093948_create_requisite_ensurance_conditions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240902_093948_create_requisite_ensurance_conditions cannot be reverted.\n";

        return false;
    }
    */
}
