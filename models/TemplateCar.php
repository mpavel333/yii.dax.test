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
class TemplateCar extends \yii\db\ActiveRecord
{
    const TYPE_CAR = 432;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template_car';
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
            self::TYPE_CAR => "Автопарк",
        ];
    }

    /**
    * @return array
    */
    public function getListTag()
    {
        return [
          '{name}' => 'Название',
          '{number}' => 'Гос. номер',
          '{status}' => 'Статус',
          '{driver_id}' => 'Водитель',
          '{mileage}' => 'Общий километраж',
          '{osago_number}' => 'Номер ОСАГО',
          '{osago_date_start}' => 'Дата начала',
          '{osago_date_end}' => 'Дата окончания',
          '{license_number}' => 'Номер прав',
          '{license_date_start}' => 'Дата начала',
          '{license_date_end}' => 'Дата окончания',
          '{is_rent}' => 'Арендованная',
          '{driver.data}' => 'Водитель ФИО',
          '{driver.data_avto}' => 'Данные автомобиля',
          '{driver.phone}' => 'Телефон',
          '{driver.driver}' => 'Водитель паспортные данные',

        ];
    }

}
