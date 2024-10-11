<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "car_to".
 *
 * @property int $id
 * @property int $car_id Авто
 * @property string $name Наименование
 * @property string $date Дата
 * @property double $mileage Километраж
 * @property string $info Информация
 * @property int $driver_id Водитель
 *
 * @property Car $car
 * @property Driver $driver
 */
class CarTo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_to';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_id', 'driver_id','response_user_id','close_user_id'], 'integer'],
            [['date'], 'safe'],
            [['mileage'], 'number'],
            [['name', 'info', 'response_user'], 'string', 'max' => 255],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Car::className(), 'targetAttribute' => ['car_id' => 'id']],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::className(), 'targetAttribute' => ['driver_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => 'Авто',
            'name' => 'Наименование',
            'date' => 'Дата',
            'mileage' => 'Километраж',
            'info' => 'Информация',
            'driver_id' => 'Водитель',
            'response_user' => 'Ответственный',
            'response_user_id' => 'Ответственный ID',
            'close_user_id' => 'Закрыл ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCar()
    {
        return $this->hasOne(Car::className(), ['id' => 'car_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(Driver::className(), ['id' => 'driver_id']);
    }
}
