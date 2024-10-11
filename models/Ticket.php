<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "ticket".
*
    * @property string $subject Заголовок
    * @property  $status Статус
    * @property int $user_id Пользователь
    * @property int $user_service_id Пользователь службы поддержки
    * @property  $create_at Дата и время
*/
class Ticket extends \app\base\ActiveRecord
{
    const STATUS_AWAIT = 1;
    const STATUS_AT_WORK = 2;
    const STATUS_DONE = 3;

    public $messageText;
    public $messageFile;

    public $fileUploading;
    

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
    * {@inheritdoc}
    */
    public static function tableNameRu()
    {
        return 'Техническая поддержка';
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
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['user_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_service_id' => 'id']],
            [['subject', 'messageText'], 'string'],
            [['status'],'filter', 'filter' => function ($value) {$result = str_replace(' ', '', $value);$result = str_replace(',', '.', $result);return $result;}],
            [['user_id', 'user_service_id'], 'integer'],
            [['create_at', 'messageFile'], 'safe'],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('app', 'Заголовок'),
            'status' => Yii::t('app', 'Статус'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'user_service_id' => Yii::t('app', 'Пользователь службы поддержки'),
            'create_at' => Yii::t('app', 'Дата и время'),
            'messageText' => Yii::t('app', 'Проблема'),
            'messageFile' => Yii::t('app', 'Файлы'),
                                                        
];
    }

    public static function relationAttributes()
    {
        return [
			'user_id' => [User::class, 'name'],
			'user_service_id' => [User::class, 'login'],
        ];
    }
    

    


    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

     

            return true;

        }

    }

    public static function statusLabels()
    {
        return [
            self::STATUS_AWAIT => 'Ожидает рассмотрения',
            self::STATUS_AT_WORK => 'В работе',
            self::STATUS_DONE => 'Закрыт',
        ];
    }


    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {
        
        if($this->isNewRecord){
            $this->create_at = date("Y-m-d H:i:s");
            $this->user_id = \Yii::$app->user->getId();
            $this->status = self::STATUS_AWAIT;
        }


        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {

        if($insert){
            $messageFiles = \yii\web\UploadedFile::getInstances($this, 'messageFile');

            $application = [];
            foreach($messageFiles as $messageFile)
            {
                $path = 'uploads/'.\Yii::$app->security->generateRandomString().'.'.$messageFile->extension;
                $application[] = [
                    'path' => $path,
                    'name' => $messageFile->name,
                ];
                $messageFile->saveAs($path);
            }
            if(count($application) == 0){
                $application = null;
            }

            $message = new TicketMessage([
                'ticket_id' => $this->id,
                'text' => $this->messageText,
                'application' => json_encode($application),
                'user_id' => \Yii::$app->user->getId(),
                'create_at' => date('Y-m-d H:i:s'),
            ]);
            $message->save(false);
        }
        
        parent::afterSave($insert, $changedAttributes);
    }





    
    
    
            
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    
            
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUserService()
    {
        return $this->hasOne(User::className(), ['id' => 'user_service_id']);
    }

    



    public function tags($text)
    {
        if (str_contains($text, '#customTable#')) {
            $text = self::customTable($text);
        }
        $json = [];
        if (str_contains($text, '#table:')) {
            $textParts = explode("#table:", $text);
            if(isset($textParts[1])){
                $textParts[1] = explode('#', $textParts[1]);
                $json = isset($textParts[1][0]) ? $textParts[1][0] : null;
                $json = str_replace("&quot;", '"', $json);
                $json = json_decode($json, true);
            }
        }
        $text = \app\helpers\TagHelper::tags($this, $text);
        if (str_contains($text, '#table#')) {
            $text = self::tableGenerate($text, $json);
        }
        return $text;
    }  

    public function tableGenerate($text, $json)
    {
        $dataInfo = \app\helpers\TagHelper::tableGenerate($text);
        $nameStructure = $dataInfo[0]; 
        $label = $dataInfo[1]; 
        $value = $dataInfo[2];

        $nameStructure = str_replace('ticket.', '', $nameStructure);
        $structure = yii\helpers\ArrayHelper::getValue($this, $nameStructure);


        $newText = "";
        $isEndTable = false;
        $isStartTable = false;
        foreach(explode("\n", $text) as $line){
            if (str_contains($line, '#table#') && !$isEndTable) {
                if (!$isStartTable) {
                    $isStartTable = true;
                } else {
                    $newText .= self::tableContent($nameStructure, $structure, $label, $value, $json);
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
        $result = '<table align="center" border="1 cellpadding="0" cellspacing="0" style="border-collapse:collapse; width:100%">
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
        if(isset($json['summary'])){
            $result .= "<tr>";
            foreach($value as $val)
            {
                $total = 0;
                $selectColumn = null;
                foreach($json['summary'] as $column)
                {
                    if($column == $val || stripos($val, "({$column})") !== false){
                        $selectColumn = $column;
                    }
                }
                if($selectColumn){
                    $result .= "<td>{$total}</td>";
                } else {
                    $result .= "<td></td>";
                }
            }
            $result .= "</tr>";
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
            if(strpos($v, 'val(') === 0){
                \Yii::warning($v, '$v');
                \Yii::warning(mb_strlen($v), 'mb_strlen');
                $params = mb_substr($v, 4, mb_strlen($v)-5);
                \Yii::warning($params, '$params');
                $result .= "\n".'<td style="vertical-align:bottom;">'.$params.'</td>';
                continue;
            }
            if(strpos($v, 'rand(') === 0){
                \Yii::warning($v, '$v');
                \Yii::warning(mb_strlen($v), 'mb_strlen');
                $args = mb_substr($v, 5, mb_strlen($v)-5);
                $args = explode(',', $args);
                $args[0] = intval(trim($args[0]));
                $args[1] = intval(trim($args[1]));
                $params = rand($args[0], $args[1]);
                $result .= "\n".'<td style="vertical-align:bottom;">'.$params.'</td>';
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