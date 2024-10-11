<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%salary}}`.
 */
class m220309_090049_create_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%salary}}', [
            'id' => $this->primaryKey(),
            'percent' => $this->double()->comment('Процент без НДС'),
            'percent_with_nds' => $this->double()->comment('Процент с НДС'),
            'withdraw' => $this->double()->comment('Снятие'),
            'user_id' => $this->integer()->comment('Пользователь'),
        ]);

        $this->createIndex(
            "idx-salary-user_id",
            "salary",
            "user_id"
        );

        $this->addForeignKey(
            "fk-salary-user_id",
            "salary",
            "user_id",
            "user",
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
            "fk-salary-user_id",
            "salary"
    	);

    	$this->dropIndex(
            "idx-salary-user_id",
            "salary"
    	);

        $this->dropTable('{{%salary}}');
    }
}
