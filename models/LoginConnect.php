<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "login_connect".
*
    * @property string $ip_address IP адрес
    * @property string $status Статус
    * @property string $login Логин
    * @property string $password Пароль
    * @property string $code Код
    * @property  $create_at Дата и время
*/
class LoginConnect extends \app\base\ActiveRecord
{

        

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'login_connect';
    }

    /**
    * {@inheritdoc}
    */
    public static function tableNameRu()
    {
        return 'Входы';
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
            [['ip_address', 'status', 'login', 'password', 'code'], 'string'],
            [['create_at'], 'safe'],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'ip_address' => Yii::t('app', 'IP адрес'),
            'status' => Yii::t('app', 'Статус'),
            'login' => Yii::t('app', 'Логин'),
            'password' => Yii::t('app', 'Пароль'),
            'code' => Yii::t('app', 'Код'),
            'create_at' => Yii::t('app', 'Дата и время'),
                                                                
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
        



        if($this->isNewRecord){
    $this->create_at = date('Y-m-d H:i:s');
}        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);
    }




    
    
    
    
    
    



    public function tags($text)
    {
        $arr = [];
        $result = preg_match_all('/\{.*?\}/',$text,$arr);
        foreach ($arr[0] as $value) {
            $func = null;
            $value2 = str_replace('{', '', $value);
            $value2 = str_replace('}', '', $value2);
            $value2 = str_replace('login_connect.', '', $value2);
            $value2 = str_replace('number_stringify(', '', $value2);
            $value2 = str_replace('to_string(', '', $value2);
            $value2 = str_replace(')', '', $value2);
            $paramsInsert =  yii\helpers\ArrayHelper::getValue($this, $value2);
            if(strpos($value, 'number_stringify(') === 1){
                $func = 'number_stringify';
            }
            if(strpos($value, 'to_string(') === 1){
                $func = 'to_string';
            }
            if($func == 'number_stringify'){
                \Yii::warning($paramsInsert, 'stringify');
                $paramsInsert = \app\helpers\Number2String::stringify($paramsInsert);
            }
            if (is_numeric($paramsInsert) && $func != 'to_string') {
                $paramsInsert = Yii::$app->formatter->asCurrency($paramsInsert, "");
            }
            $text = str_replace($value, $paramsInsert, $text);

            // Date function formmating
            $dateMatches = [];
            preg_match_all('/#date\(("|\'|&quot;).*("|\'|&quot;)\)#/', $text, $dateMatches);
            if(isset($dateMatches[0])){
                if(isset($dateMatches[0][0])){
                    $dateValue = $dateMatches[0][0];
                    $dateValue = preg_replace('/#date\(("|\'|&quot;)/', '', $dateValue);
                    $dateValue = preg_replace('/("|\'|&quot;)\)#/', '', $dateValue);
                    setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');  
                    $realDate = \Yii::$app->formatter->asDate(time(), "php:{$dateValue}");
                    $text = str_replace($dateMatches[0][0], $realDate, $text);
                }
            }
        }
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

        $nameStructure = str_replace('login_connect.', '', $nameStructure);
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
        $result = '<table align="center" border="1 cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-left:5%; width:90%">
                    <tbody>
                        <tr>';
        foreach ($label as $v) {
            $result .= "\n".\app\helpers\TagHelper::tableGLabel($v);
        }
        $result .= '</tr>';
        foreach ($structure as $v) {
            $result .= "\n".self::tableGValue($nameStructure, $v, $value);
        }
        $result .= '</tbody>
                </table>';
        return $result;
    }  


    

    public function tableGValue($nameStructure, $line, $list){
        $result = '<tr>';
        foreach ($list as $v) {
            $params = \yii\helpers\ArrayHelper::getValue($line, $v);
            if (is_numeric($params)) {
                $params = Yii::$app->formatter->asCurrency($params, "");
            }
            $result .= "\n".'<td style="vertical-align:bottom;">'.$params.'</td>';
        }
        $result .= '</tr>';
        return $result;
    }


}