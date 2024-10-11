<?php 
namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name Название
 * @property integer $requisite_create Реквизиты Создание
 * @property integer $requisite_update Реквизиты Изменение
 * @property integer $requisite_view Реквизиты Просмотр
 * @property integer $requisite_view_all Реквизиты Просмотр всех
 * @property integer $requisite_delete Реквизиты Удаление
 * @property integer $requisite_disallow_fields Реквизиты Исключенные поля
 * @property integer $client_create Организации Создание
 * @property integer $client_update Организации Изменение
 * @property integer $client_view Организации Просмотр
 * @property integer $client_view_all Организации Просмотр всех
 * @property integer $client_delete Организации Удаление
 * @property integer $client_disallow_fields Организации Исключенные поля
 * @property integer $driver_create Водители Создание
 * @property integer $driver_update Водители Изменение
 * @property integer $driver_view Водители Просмотр
 * @property integer $driver_view_all Водители Просмотр всех
 * @property integer $driver_delete Водители Удаление
 * @property integer $driver_disallow_fields Водители Исключенные поля
 * @property integer $flight_create Рейсы Создание
 * @property integer $flight_update Рейсы Изменение
 * @property integer $flight_view Рейсы Просмотр
 * @property integer $flight_view_all Рейсы Просмотр всех
 * @property integer $flight_delete Рейсы Удаление
 * @property integer $flight_disallow_fields Рейсы Исключенные поля
 * @property integer $books Справочники
 *
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{

    public $plan_report_disallow_fields;
    public $flight_search_disallow_fields;
    public $structure_disallow_fields;

    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requisite_create', 'requisite_update', 'requisite_view', 'requisite_view_all', 'requisite_delete', 'client_create', 'client_update', 'client_view', 'client_view_all', 'client_delete', 'driver_create', 'driver_update', 'driver_view', 'driver_view_all', 'driver_delete', 'flight_create', 'flight_update', 'flight_view', 'flight_view_all', 'flight_delete', 'flight_payment_check', 'books', 'settings', 'security', 'mail_create', 'mail_update', 'mail_view', 'mail_view_all', 'mail_delete', 'mail_disallow_fields', 'dashboard', 'flight_export', 'flight_is_order', 'flight_is_signature', 'flight_upload_file', 'flight_driver_upload_file', 'car', 'rent_car', 'flight_table', 'flight_dates', 'flight_order', 'flight_driver_order', 'flight_driver_signature', 'flight_orders_show', 'security_table', 'login', 'flight_role_table', 'flight_prepayment', 'calls', 'flight_role_table', 'flight_group_table', 'flight_statistic', 'flight_btn_print', 'flight_btn_update', 'flight_btn_export', 'flight_btn_print_pdf', 'flight_btn_copy', 'flight_btn_delete', 'flight_btn_archive', 'flight_btn_api',  'metal_create', 'metal_update', 'metal_view', 'metal_view_all', 'metal_delete', 'flight_btn_update_permament', 'flight_btn_signature', 'holiday_create', 'holiday_update', 'holiday_view', 'holiday_view_all', 'holiday_delete', 'flight_date_validation','flight_check_salary','flight_check_recoil','flight_check_insurance','flight_check_additional_credit', 'flight_btn_permament_delete', 'client_limit_visible', 'flight_disable_validation', 'flight_checks', 'flight_checks1', 'flight_checks2', 'client_contract', 'flight_checks3', 'flight_chat', 'ticket_manager', 'flight_archive', 'client_work', 'users',
              'car_to_create', 'car_to_update', 'car_to_view', 'car_to_view_all', 'car_to_delete','car_to_responsible', 'lawyer', 'client_control_all','structure_create', 'structure_update', 'structure_view', 'structure_view_all', 'structure_delete'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['name'], 'required'],
            [['requisite_disallow_fields', 'client_disallow_fields', 'driver_disallow_fields', 'flight_disallow_fields', 'metal_disallow_fields', 'flight_manager_change', 'holiday_disallow_fields', 'docs', 'docs1', 'docs2', 'docs3', 'docs_readonly', 'docs1_readonly', 'docs2_readonly', 'docs3_readonly'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Роль',
            'requisite_create' => 'Создание',
            'requisite_update' => 'Изменение',
            'requisite_view' => 'Просмотр',
            'requisite_view_all' => 'Просмотр всех',
            'requisite_delete' => 'Удаление',
            'mail_create' => 'Создание',
            'mail_update' => 'Изменение',
            'mail_view' => 'Просмотр',
            'mail_view_all' => 'Просмотр всех',
            'mail_delete' => 'Удаление',
            'mail_disallow_fields' => 'Исключенные поля',
            'client_create' => 'Создание',
            'client_update' => 'Изменение',
            'client_view' => 'Просмотр',
            'client_view_all' => 'Просмотр всех',
            'client_delete' => 'Удаление',
            'client_contract'=>'Договор (галочка)',
            'client_control_all'=>'Контроль всех',
            'driver_create' => 'Создание',
            'driver_update' => 'Изменение',
            'driver_view' => 'Просмотр',
            'driver_view_all' => 'Просмотр всех',
            'driver_delete' => 'Удаление',
            'flight_create' => 'Создание',
            'flight_update' => 'Изменение',
            'flight_view' => 'Просмотр',
            'flight_view_all' => 'Просмотр всех',
            'flight_delete' => 'Удаление',
            'flight_payment_check' => 'Галочки оплаты',
            'flight_order' => 'Поле "Заявка"',
            'flight_driver_order' => 'Поле "Заявка перевозчика"',
            'flight_driver_signature' => 'Галочка "Подпись перевозчика"',
            'flight_orders_show' => 'Раздел "Заявки"',
                    'requisite_disallow_fields' => 'Исключенные поля',
            'client_disallow_fields' => 'Исключенные поля',
            'client_limit_visible' => 'Лимит',
            'driver_disallow_fields' => 'Исключенные поля',
            'flight_disallow_fields' => 'Исключенные поля',
            'flight_manager_change' => 'Изменение менеджера',
            'books' => 'Справочники',
            'settings' => 'Настройки',
            'security' => 'Безопасность',
            'dashboard' => 'Рабочий стол',
            'flight_export' => 'Экспорт рейсов',
            'flight_upload_file' => 'Загрузка файлов заказчика',
            'flight_driver_upload_file' => 'Загрузка файлов перевозчика',
            'flight_is_order' => 'Заявка',
            'flight_is_signature' => 'Подпись',
            'flight_group_table' => 'Группа',
            'car' => 'Собственный автопарк',
            'rent_car' => 'Арендованный автопарк',
            'flight_table' => 'Настройки таблицы',
            'flight_dates' => 'Поля дат',
            'flight_role_table' => 'Табель',
            'flight_statistic' => 'Статистика',
            'flight_btn_print' => 'Иконка «Печать»',
            'flight_btn_update' => 'Иконка «Редактировать»',
            'flight_btn_update_permament' => 'Иконка «Редактировать» (Постоянное)',
            'flight_btn_export' => 'Иконка «Экспорт»',
            'flight_btn_print_pdf' => 'Иконка «Печать PDF»',
            'flight_btn_copy' => 'Иконка «Копировать»',
            'flight_btn_delete' => 'Иконка «Удалить»',
            'flight_btn_permament_delete' => 'Иконка «Удалить» (Постоянная)',
            'flight_btn_archive' => 'Иконка «Архив»',
            'flight_btn_api' => 'Иконка «API»',
            'flight_btn_signature' => 'Иконка «Подпись»',
            'flight_date_validation' => 'Валидация дат',
            'flight_check_salary' => 'Галочка зарплаты',
            'flight_check_recoil' => 'Галочка баллов',
            'flight_check_insurance' => 'Галочка страховки',
            'flight_check_additional_credit' => 'Галочка доп. расходов',
            'flight_disable_validation' => 'Отключить валидацию',
            'flight_checks' => 'Документы',
            'flight_checks1' => 'Документы 1',
            'flight_checks2' => 'Документы 2',
            'flight_checks3' => 'Документы 3',
            'flight_archive' => 'Архив',

            'metal_create' => 'Создание',
            'metal_update' => 'Изменение',
            'metal_view' => 'Просмотр',
            'metal_view_all' => 'Просмотр всех',
            'metal_delete' => 'Удаление',
            'metal_disallow_fields' => 'Исключенные поля',

            'holiday_create' => 'Создание',
            'holiday_update' => 'Изменение',
            'holiday_view' => 'Просмотр',
            'holiday_view_all' => 'Просмотр всех',
            'holiday_delete' => 'Удаление',
            'holiday_disallow_fields' => 'Исключенные поля',

            'security_table' => 'Безопасность входов',
            'login' => 'Входы',
            'flight_prepayment' => 'Предоплата',
            'calls' => 'Звонки',
            'docs' => 'Документы',
            'docs1' => 'Документы 1',
            'docs2' => 'Документы 2',
            'docs3' => 'Документы 3',
            'docs_readonly' => 'Только для чтения',
            'docs1_readonly' => 'Только для чтения 1',
            'docs2_readonly' => 'Только для чтения 2',
            'docs3_readonly' => 'Только для чтения 3',
            'ticket_manager' => 'Менеджер службы поддержки',
            'client_work' => 'Работа с клиентом',
            'users' => 'Пользователи',

            'flight_chat' => 'Чат',

            'car_to_create' => 'Создание',
            'car_to_update' => 'Изменение',
            'car_to_view' => 'Просмотр',
            'car_to_view_all' => 'Просмотр всех',
            'car_to_delete' => 'Удаление',
            'car_to_responsible'=>'Ответственный',
            
            'lawyer' => 'Юристы',
            
            'structure_create' => 'Создание',
            'structure_update' => 'Изменение',
            'structure_view' => 'Просмотр',
            'structure_view_all' => 'Просмотр всех',
            'structure_delete' => 'Удаление',            
            
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function beforeSave($insert) {
        if($this->requisite_disallow_fields && is_array($this->requisite_disallow_fields)){
            $this->requisite_disallow_fields = implode(',', $this->requisite_disallow_fields);
        }
        if($this->client_disallow_fields && is_array($this->client_disallow_fields)){
            $this->client_disallow_fields = implode(',', $this->client_disallow_fields);
        }
        if($this->driver_disallow_fields && is_array($this->driver_disallow_fields)){
            $this->driver_disallow_fields = implode(',', $this->driver_disallow_fields);
        }
        if($this->flight_disallow_fields && is_array($this->flight_disallow_fields)){
            $this->flight_disallow_fields = implode(',', $this->flight_disallow_fields);
        }
        if($this->docs && is_array($this->docs)){
            $this->docs = implode(',', $this->docs);
        }
        if($this->docs1 && is_array($this->docs1)){
            $this->docs1 = implode(',', $this->docs1);
        }
        if($this->docs2 && is_array($this->docs2)){
            $this->docs2 = implode(',', $this->docs2);
        }
        if($this->docs3 && is_array($this->docs3)){
            $this->docs3 = implode(',', $this->docs3);
        }
        if($this->docs_readonly && is_array($this->docs_readonly)){
            $this->docs_readonly = implode(',', $this->docs_readonly);
        }
        if($this->docs1_readonly && is_array($this->docs1_readonly)){
            $this->docs1_readonly = implode(',', $this->docs1_readonly);
        }
        if($this->docs2_readonly && is_array($this->docs2_readonly)){
            $this->docs2_readonly = implode(',', $this->docs2_readonly);
        }
        if($this->docs3_readonly && is_array($this->docs3_readonly)){
            $this->docs3_readonly = implode(',', $this->docs3_readonly);
        }


        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['role_id' => 'id']);
    }


    public static function getRoleFromCache($id=null)
    {
        $roles = Yii::$app->myCache->get('roles');

        foreach($roles as $role){
            if($role->id == Yii::$app->user->identity->role_id)
            break;
        }

        if($id):
            return $role;
        else:
            return $roles;
        endif;          

    }



}
