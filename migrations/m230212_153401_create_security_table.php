<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%security}}`.
 */
class m230212_153401_create_security_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('security', [
            'id' => $this->primaryKey(),
            'token' => $this->string()->comment('Токен бота'),
            'admin_id' => $this->string()->comment('ID Админа'),
        ]);

        

$this->insert('security', ['token' => '2067378858:AAF2d3xqF8CSVFjYsaDMQMUm6W2-yYC0KRs','admin_id' => '247187885',]);    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        
        $this->dropTable('security');
    }
}
