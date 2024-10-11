<?php
use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m210901_155441_create_report_column_table extends Migration
{
    /**
     * @inheritdoc 
     */
    public function up()
    {
        $this->createTable('report_column', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Наименование'),
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('report_column');
    }
}
