<?php 
namespace app\models;

use Yii;

/**
 * This is the model class for table "template_car".
 *
 * @property int $id
 * @property string $name Название
 * @property int $type Тип
 * @property string $text Текст
 */
class TemplateClient extends \yii\db\ActiveRecord
{
    const TYPE_CLIENT = 432;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['text'], 'string'],
            [['name', 'modifier'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'type' => 'Тип',
            'text' => 'Текст',
            'modifier' => 'Модификация',
        ];
    }

    /**
    * @return array
    */
    public static function typeLabels()
    {
        return [
            self::TYPE_CLIENT => "Клиенты",
        ];
    }

    /**
    * @return array
    */
    public function getListTag()
    {
        return [
          '{name}' => 'Наименование',
          '{name_case}' => 'Наименование (Род. п.)',
          '{doljnost_rukovoditelya}' => 'Должность руководителя',
          '{fio_polnostyu}' => 'ФИО полностью',
          '{initials}' => 'Инициалы',
          '{official_address}' => 'Юридический адрес',
          '{bank_name}' => 'Наименование банка',
          '{inn}' => 'ИНН',
          '{kpp}' => 'КПП',
          '{ogrn}' => 'ОГРН',
          '{bic}' => 'Бик',
          '{kr}' => 'КР',
          '{nomer_rascheta}' => 'Номер расчета',
          '{tel}' => 'тел.',
          '{email}' => 'email',
          '{nds}' => 'НДС',
          '{doc}' => 'Договор',
          '{mailing_address}' => 'Почтовый адрес',
          '{code}' => 'Код АТИ',
        ];
    }
}
