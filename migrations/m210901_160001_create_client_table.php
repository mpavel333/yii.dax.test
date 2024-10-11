<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210901_160001_create_client_table`.
 */
class m210901_160001_create_client_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('client', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Наименование'),
            'doljnost_rukovoditelya' => $this->string()->comment('Должность руководителя'),
            'fio_polnostyu' => $this->string()->comment('ФИО полностью'),
            'official_address' => $this->string()->comment('Юридический адрес'),
            'bank_name' => $this->string()->comment('Наименование банка'),
            'inn' => $this->string()->comment('ИНН'),
            'kpp' => $this->string()->comment('КПП'),
            'ogrn' => $this->string()->comment('ОГРН'),
            'bic' => $this->string()->comment('Бик'),
            'kr' => $this->string()->comment('КР'),
            'nomer_rascheta' => $this->string()->comment('Номер расчета'),
            'tel' => $this->string()->comment('тел.'),
            'email' => $this->string()->comment('email'),
            'nds' => $this->boolean()->comment('НДС'),
            'doc' => $this->string()->comment('Договор'),
            'mailing_address' => $this->string()->comment('Почтовый адрес'),
            'code' => $this->string()->comment('Код АТИ'),
        ]);

        

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        
        $this->dropTable('client');
    }
}
