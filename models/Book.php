<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the book model class.
*
* @property string $name Название
*/
class Book extends \app\base\ActiveRecord
{
    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        if(isset(\Yii::$app->controller->tableName)){
            return \Yii::$app->controller->tableName;
        }

        return null;
    }
    

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', isset(\Yii::$app->controller->attributeLabel) ? \Yii::$app->controller->attributeLabel : 'Название'),
        ];
    }

    public static function findIn($tbl)
    {
        return (new \yii\db\Query)->from($tbl);
    }

    

    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {
        


 
        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {


    }

    public function tags($text)
    {
        $arr = [];
        $result = preg_match_all('/\{.*?\}/',$text,$arr);
        foreach ($arr[0] as $value) {
            $value2 = str_replace('{', '', $value);
            $value2 = str_replace('}', '', $value2);
            $value2 = str_replace('brand.', '', $value2);
            $text = str_replace($value, yii\helpers\ArrayHelper::getValue($this, $value2), $text);
        }

        return $text;
    }  
}