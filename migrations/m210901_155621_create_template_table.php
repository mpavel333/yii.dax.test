<?php 
use yii\db\Migration;

/**
 * Handles the creation of table `template`.
 */
class m210901_155621_create_template_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('template', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'type' => $this->integer()->comment('Тип'),
            'text' =>  'LONGTEXT',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('template');
    }
}
