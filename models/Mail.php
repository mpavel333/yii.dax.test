<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
* This is the model class for table "mail".
*
    * @property string $number Номер
    * @property string $organization_name Название организации
    * @property string $from От кого
    * @property string $to Кому
    * @property string $track № Трека
    * @property  $when_send Когда отправили
    * @property  $when_receive Когда получили
*/
class Mail extends \app\base\ActiveRecord
{

    const STATUS_SENT = 1;
    const STATUS_RECEIVED = 2;

    public $fileUploading;
    

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'mail';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['number', 'organization_name', 'from', 'to', 'track', 'when_send', 'when_receive', 'information', 'upd'], 'string'],
            [['status', 'files'], 'safe'],
            [['client_id', 'user_id'],'integer']
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'number' => Yii::t('app', 'Номер'),
            'organization_name' => Yii::t('app', 'Название организации'),
            'from' => Yii::t('app', 'От кого'),
            'to' => Yii::t('app', 'Информация письма'),
            'track' => Yii::t('app', '№ Трека'),
            'when_send' => Yii::t('app', 'Когда отправили'),
            'when_receive' => Yii::t('app', 'Когда получили'),
            'status' => Yii::t('app', 'Статус'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'files' => Yii::t('app', 'Файлы'),
            'client_id' => Yii::t('app', 'Название организации'),
            'information' => Yii::t('app', 'Информация письма'),
            'upd' => Yii::t('app', 'УПД'),
        ];
    }

    

    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {

        if($this->isNewRecord){
            $this->user_id = \Yii::$app->user->getId();
        }

        if(isset($_POST['files_file_path'])){
            $this->files = json_decode($this->files, true);
            $newfile = json_decode($_POST['files_file_path'], true);
            $this->files = json_encode(\yii\helpers\ArrayHelper::merge($this->files ? $this->files : [], $newfile ? $newfile : []), JSON_UNESCAPED_UNICODE);
        }

        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {

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
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
    
    
    
    


    public function tags($text)
    {
        $arr = [];
        $result = preg_match_all('/\{.*?\}/',$text,$arr);
        foreach ($arr[0] as $value) {
            $value2 = str_replace('{', '', $value);
            $value2 = str_replace('}', '', $value2);
            $text = str_replace($value, yii\helpers\ArrayHelper::getValue($this, $value2), $text);
        }

        return $text;
    }  

}