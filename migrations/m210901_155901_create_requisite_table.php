<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210901_155901_create_requisite_table`.
 */
class m210901_155901_create_requisite_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('requisite', [
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
            'fio_buhgaltera' => $this->string()->comment('ФИО бухгалтера'),
            'nds' => $this->boolean()->comment('НДС'),
            'pechat' => $this->string()->comment('Печать'),
        ]);

        

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        
        $this->dropTable('requisite');
    }
}
