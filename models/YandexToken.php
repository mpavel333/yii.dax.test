<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yandex_token".
 *
 * @property int $id
 * @property string $token Токен
 */
class YandexToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yandex_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            [['token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Токен',
        ];
    }
}
