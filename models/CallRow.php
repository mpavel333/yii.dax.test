<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "call_row".
 *
 * @property int $id
 * @property int $call_id Звонок
 * @property string $text Текст
 * @property string $datetime Дата и время
 */
class CallRow extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'call_row';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['call_id'], 'integer'],
            [['datetime'], 'safe'],
            [['text'], 'string', 'max' => 255],
            [['call_id'], 'exist', 'skipOnError' => true, 'targetClass' => Call::className(), 'targetAttribute' => ['call_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'call_id' => 'Звонок',
            'text' => 'Текст',
            'datetime' => 'Дата и время',
        ];
    }
}