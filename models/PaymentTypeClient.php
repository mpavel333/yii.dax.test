<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_type_client".
 *
 * @property int $id
 * @property string $name Название
 */
class PaymentTypeClient extends Book
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_type_client';
    }
}