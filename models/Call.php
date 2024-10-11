<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "call".
 *
 * @property int $id
 * @property string $phone Телефон
 * @property string $phone1 Телефон1
 * @property string $phone2 Телефон2
 * @property int $client_id Клиент
 * @property int $status Статус
 * @property string $inn ИНН
 * @property string $site Сайт
 * @property string $industry Отрасль
 * @property string $city Город
 * @property string $contact_name Контакт
 * @property string $timezone Часовой пояс
 * @property string $result Результат
 * @property resource $files Файлы
 * @property int $user_id Пользователь
 * @property string $create_at Дата и время
 */
class Call extends \yii\db\ActiveRecord
{

    public $fileUploading;

    public $rows;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'call';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'status', 'user_id'], 'integer'],
            [['files'], 'string'],
            [['create_at'], 'safe'],
            [['phone', 'phone1', 'phone2', 'inn', 'post', 'site', 'industry', 'region', 'city', 'contact_name', 'timezone', 'result', 'result_text'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['fileUploading', 'rows'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Телефон и контакт',
            'phone1' => 'Телефон1',
            'phone2' => 'Телефон2',
            'client_id' => 'Клиент',
            'status' => 'Статус',
            'inn' => 'ИНН',
            'post' => 'Почта',
            'site' => 'Сайт',
            'industry' => 'Отрасль',
            'region' => 'Область',
            'city' => 'Город',
            'contact_name' => 'Контакт',
            'timezone' => 'Часовой пояс',
            'result' => 'Результат',
            'result_text' => 'Текст результата',
            'files' => 'Файлы',
            'user_id' => 'Пользователь',
            'create_at' => 'Дата и время',
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {
        if(isset($_POST['file_file_path'])){
            $this->files = json_decode($this->files, true);
            $newfile = json_decode($_POST['file_file_path'], true);
            $this->files = json_encode(\yii\helpers\ArrayHelper::merge($this->files ? $this->files : [], $newfile ? $newfile : []), JSON_UNESCAPED_UNICODE);
        }

        if($this->result){
        	if($this->rows == null){
        		$this->rows = [];
        	}
            $this->rows[] = [
                'id' => null,
                'text' => $this->result,
                'datetime' => date('Y-m-d H:i:s'),
            ];
            $this->phone = null;
            $this->result = null;
        }

        return parent::beforeSave($insert);
    }

    public static function contactNameList()
    {
        return [
            'ЛПР' => 'ЛПР',
            'Менеджер' => 'Менеджер',
            'Секретарь' => 'Секретарь',
            'Начальник отдела' => 'Начальник отдела',
            'Логист' => 'Логист',
            'Директор' => 'Директор',
            'Служба безопасности' => 'Служба безопасности',
        ];
    }

    public static function resultList()
    {
        return [
            'Работа с клиентом' => 'Работа с клиентом',
            'Не берут трубку' => 'Не берут трубку',
            'Нужно перезвонить' => 'Нужно перезвонить',
            'Пока не нуждается' => 'Пока не нуждается',
            'Делаем просчет' => 'Делаем просчет',
            'Мы работаем уже с другими' => 'Мы работаем уже с другими',
            'Есть свой автопарк' => 'Есть свой автопарк',
            'Самовывоз' => 'Самовывоз',
            'Мелкие груза работаем с Пэк и т.д.' => 'Мелкие груза работаем с Пэк и т.д.',
        ];
    }

    public static function statusLabels()
    {
        return [
            1 => 'В работе',
            2 => 'Мой заказчик',
            3 => 'Архив',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }


    public function afterSave($insert, $changedAttributes)
    {
        if ($this->rows != null){
            $bankAll = CallRow::find()->where(['call_id' => $this->id])->all();
            foreach ($bankAll as $item1) {
                $a = false;
                foreach ($this->rows as $item3) {
                    if ($item3['id'] == $item1->id) {
                        $a = true;
                    }
                }
                if (!$a) {
                    $item1->delete();
                }
            }
            
            $counter = 0;



            foreach ($this->rows as $item) {
                if ($item['id'] == '') $item['id'] = null;
                $bank = CallRow::find()->where(['id' => $item['id']])->one();

                $selection = isset($item['selection']) ? implode(',', $item['selection']) : null;

                if (!$bank) {
                    $itemNew = new CallRow($item);
                    $itemNew->call_id = $this->id;
                    $itemNew->save();
                } else {
                    $bank->text = isset($item['text']) ? $item['text'] : null;
                    $bank->datetime = isset($item['datetime']) ? $item['datetime'] : null;

                    $bank->save();
                }

                $counter++;
            }

        } else {
 
            if ($this->rows) {
                CallRow::deleteAll(['call_id' => $this->id]);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }
    

}