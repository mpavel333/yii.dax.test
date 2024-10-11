<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mail}}`.
 */
class m220309_134513_create_mail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mail', [
            'id' => $this->primaryKey(),
            'number' => $this->string()->comment('Номер'),
            'organization_name' => $this->string()->comment('Название организации'),
            'from' => $this->string()->comment('От кого'),
            'to' => $this->string()->comment('Кому'),
            'track' => $this->string()->comment('№ Трека'),
            'when_send' => $this->date()->comment('Когда отправили'),
            'when_receive' => $this->date()->comment('Когда получили'),
        ]);

        

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mail');
    }
}
