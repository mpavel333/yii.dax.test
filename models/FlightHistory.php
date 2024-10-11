<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "flight_history".
 *
 * @property int $id
 * @property int $flight_id Рейсы
 * @property resource $data Данные
 * @property int $user_id Пользователь
 * @property string $create_at Дата и время
 *
 * @property Flight $flight
 * @property User $user
 */
class FlightHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'flight_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flight_id', 'user_id'], 'integer'],
            [['data'], 'string'],
            [['create_at'], 'safe'],
            [['flight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Flight::className(), 'targetAttribute' => ['flight_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'flight_id' => Yii::t('app', 'Рейсы'),
            'data' => Yii::t('app', 'Данные'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'create_at' => Yii::t('app', 'Дата и время'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFlight()
    {
        return $this->hasOne(Flight::className(), ['id' => 'flight_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
