<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "holiday".
*
    * @property  $date Дата
*/
class Holiday extends \app\base\ActiveRecord
{

    public $fileUploading;
    

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'holiday';
    }

    /**
    * {@inheritdoc}
    */
    public static function tableNameRu()
    {
        return 'Праздники';
    }

    /**
    * {@inheritdoc}
    */
    public static function getIsLog()
    {
        return 1;
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['date'], 'safe'],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'date' => Yii::t('app', 'Дата'),
                        
];
    }

    public static function relationAttributes()
    {
        return [
        ];
    }
    

    


    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

 

            return true;

        }

    }



    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {
        



        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);
    }

    public static function nextDate(&$dateTime)
    {
        $holiday = self::find()->where(['date' => $dateTime->format('Y-m-d')])->one();
        if($holiday){
            $dateTime->modify("+1 days");
            self::nextDate($dateTime);
        }
    }


    



    public function tags($text)
    {
        if (str_contains($text, '#customTable#')) {
            $text = self::customTable($text);
        }
        $text = \app\helpers\TagHelper::tags($this, $text);
        if (str_contains($text, '#table#')) {
            $text = self::tableGenerate($text);
        }
        return $text;
    }  

    public function tableGenerate($text)
    {
        $dataInfo = \app\helpers\TagHelper::tableGenerate($text);
        $nameStructure = $dataInfo[0]; 
        $label = $dataInfo[1]; 
        $value = $dataInfo[2];

        $nameStructure = str_replace('holiday.', '', $nameStructure);
        $structure = yii\helpers\ArrayHelper::getValue($this, $nameStructure);


        $newText = "";
        $isEndTable = false;
        $isStartTable = false;
        foreach(explode("\n", $text) as $line){
            if (str_contains($line, '#table#') && !$isEndTable) {
                if (!$isStartTable) {
                    $isStartTable = true;
                } else {
                    $newText .= self::tableContent($nameStructure, $structure, $label, $value);
                    $isStartTable = false;
                    $isEndTable = true;
                    continue;
                }
            }
            if (!$isStartTable) {
                $newText .= $line;
            }
        }
        
        return $newText;
    }  
    public function tableContent($nameStructure, $structure, $label,$value)
    {
        $system_number = 1;
        $result = '<table align="center" border="1 cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-left:5%; width:90%">
                    <tbody>
                        <tr>';
        foreach ($label as $v) {
            $result .= "\n".\app\helpers\TagHelper::tableGLabel($v);
        }
        $result .= '</tr>';
        foreach ($structure as $v) {
            $result .= "\n".self::tableGValue($nameStructure, $v, $value, $system_number);
            $system_number += 1;
        }
        $result .= '</tbody>
                </table>';
        return $result;
    }  



    
    public function tableGValue($nameStructure, $line, $list, $system_number){
        $result = '<tr>';
        foreach ($list as $v) {
            if ($v == "system_number") {
                $result .= "\n".'<td style="vertical-align:bottom;">'.$system_number.'</td>';
                continue;
            }
            if(substr(trim($v), 0, 1) == '-'){
                $params = '-'.\yii\helpers\ArrayHelper::getValue($line, substr(trim($v), 1, strlen($v)-1));
            } else {
                $params = \yii\helpers\ArrayHelper::getValue($line, $v);
            }
            $isId = (strlen($v) > 3 && substr($v, strlen($v)-2) == 'id' && substr($v, strlen($v)-3) != '_id') ? true : false; // Является ли поле айдишником
            if (is_numeric($params) && $isId == false) {
                $params = Yii::$app->formatter->asCurrency($params, "");
            }
            $result .= "\n".'<td style="vertical-align:bottom;">'.$params.'</td>';
        }
        $result .= '</tr>';
        return $result;
    }



}