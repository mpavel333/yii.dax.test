<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank}}`.
 */
class m220803_171542_create_bank_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('Пользователь'),
            'name' => $this->string()->comment('Наименование'),
            'inn' => $this->string()->comment('ИНН'),
            'kpp' => $this->string()->comment('КПП'),
            'ogrn' => $this->string()->comment('ОГРН'),
            'bik' => $this->string()->comment('Бик'),
            'kr' => $this->string()->comment('КП'),
            'number' => $this->string()->comment('Номер расчета'),
        ]);

        $this->createIndex(
            "idx-bank-user_id",
            "bank",
            "user_id"
        );

        $this->addForeignKey(
            "fk-bank-user_id",
            "bank",
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
            "fk-bank-user_id",
            "bank"
    	);

    	$this->dropIndex(
            "idx-bank-user_id",
            "bank"
    	);


        $this->dropTable('{{%bank}}');
    }
}
