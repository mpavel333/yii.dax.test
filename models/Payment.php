<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $client_id Клиент
 * @property int $type Тип
 * @property double $amount Сумма
 * @property string $payment Плательщик
 * @property string $payment_inn ИНН плательщика
 * @property string $receiver Получатель
 * @property string $receiver_inn ИНН получателя
 * @property string $date Дата
 *
 * @property Client $client
 */
class Payment extends \yii\db\ActiveRecord
{
    const TYPE_DEBIT = 1;
    const TYPE_CREDIT = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'type'], 'integer'],
            [['amount'], 'number'],
            [['date'], 'safe'],
            [['payment', 'payment_inn', 'receiver', 'receiver_inn'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Клиент',
            'type' => 'Тип',
            'amount' => 'Сумма',
            'payment' => 'Плательщик',
            'payment_inn' => 'ИНН плательщика',
            'receiver' => 'Получатель',
            'receiver_inn' => 'ИНН получателя',
            'date' => 'Дата',
        ];
    }

    /**
     * @return array
     */
    public static function typeLabels()
    {
        return [
            self::TYPE_DEBIT => "Доход",
            self::TYPE_CREDIT => "Расход",
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
}
