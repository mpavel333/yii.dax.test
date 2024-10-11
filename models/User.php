<?php 

namespace app\models;

use app\helpers\TagHelper;
use http\Exception;
use SendGrid\Mail\Mail;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use app\components\MyCache;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $login Логин
 * @property integer $role_id Роль
 * @property string $role Должность
 * @property string $name ФИО
 * @property string $phone Телефон
 * @property integer $access Доступ
 * @property string $password_hash Зашифрованный пароль
 * @property string $created_at Дата создания
 * @property integer $is_deletable Можно удалить или нельзя
 *
 *
 * @property User $identity
 */
class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_EDIT = 'edit';

    const PERCENT_CLASSIC = 1;
    const PERCENT_DYNAMIC = 2;
    const PERCENT_PERCENT = 3;
    const PERCENT_PERCENT_HALF = 4;
    const PERCENT_DISPATCH = 5;

    const ROLE_ADMIN = 1;

    public $inn;
    public $organizationName;

    public $password;

    private $oldPasswordHash;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ["login", "role_id", "role", "name", "phone", "access", "password_hash", 'password', "created_at", "is_deletable", 'percent_salary', 'email', 'percent', 'token', 'mail_host', 'mail_pass', 'mail_port', 'mail_encryption', 'client_id', 'inn', 'organizationName', 'daks_balance', 'post_address', 'group', 'date_of_birth', 'dolzhnost'],
            self::SCENARIO_EDIT => ["login", "role_id", "role", "name", "phone", "access", "password_hash", 'password', "created_at", "is_deletable", 'percent_salary', 'email', 'percent', 'token',  'mail_host', 'mail_pass', 'mail_port', 'mail_encryption', 'client_id', 'inn', 'organizationName', 'daks_balance', 'post_address', 'group', 'date_of_birth', 'dolzhnost'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login'], 'required'],
            //[['password'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['login'], 'unique'],
            [['email'], 'email'],
            [['is_deletable', 'role_id', 'client_id'], 'integer'],
            [['percent', 'daks_balance'], 'number'],
            [['login', 'password_hash', 'password', 'name', 'phone', 'percent_salary', 'password_open', 'new_password', 'token', 'mail_host', 'mail_pass', 'mail_port', 'mail_encryption', 'inn', 'organizationName', 'post_address', 'group', 'dolzhnost'], 'string', 'max' => 255],
            [['role'], function(){
                if($this->role == $this->group && $this->role != null && $this->group != null){
                    $this->addError('role', 'Не должно совпадать с группой');
                }
            }],
            [["role_id", "role", "access", "created_at", "is_deletable", 'email', 'date_of_birth'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        parent::beforeDelete();

        $uid = Yii::$app->user->identity->id;

        if($uid == $this->id)
        {
            Yii::$app->session->setFlash('error', "Вы авторизованы под пользователем «{$this->login}». Удаление невозможно!");
            return false;
        }

        if($this->id == 1)
        {
            Yii::$app->session->setFlash('error', "Этот пользователь не может подлежать удалению. Удаление невозможно!");
            return false;
        } else {
            return true;
        }

    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role_id === self::ROLE_ADMIN;
    }

    /**
     * @return bool
     */
    public function isAdministration()
    {
        /** @var User $identity */
        $identity = Yii::$app->user->identity;

        return $identity->role === self::ROLE_ADMIN;
    }


    /**
     * @return bool
     */
    public function isLimitedManager()
    {
        return Yii::$app->user->identity->role === self::ROLE_LIMITED_MANAGER;
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->oldPasswordHash = $this->password_hash;
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getRoleName()
    {
        //$role = Role::findOne($this->role_id);
        
        $role = Yii::$app->cache->get('getRoleName');
        if($role == null){
            $role = Role::findOne($this->role_id);
            Yii::$app->cache->set('getRoleName',$role);
        }
        
        return $role ? $role->name : null;        
    }

    public function isManager(){
        $name = $this->getRoleName();

        // return mb_stripos($name, 'менеджер') !== false || mb_stripos($name, 'бухгалтерия') !== false;
        return mb_stripos($name, 'менеджер') !== false || $this->can('flight_btn_update');
    }

    public function isSignaturer(){
        $name = $this->getRoleName();

        return mb_stripos($name, 'подписант') !== false;
    }

    public function isClient(){
        $name = $this->getRoleName();

        return mb_stripos($name, 'заказчик') !== false && $this->isManager() == false;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        Yii::info($this->password, 'Пароль');
        if($this->password != null){
            Yii::info('Пароль перед сохранением: ' . $this->password, 'test');
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $this->token = md5($this->login).md5($this->password);
        } else {
            $this->password_hash = $this->oldPasswordHash;
        }

        if(\Yii::$app->controller->module->id == 'mobile'){
            if(($this->inn || $this->organizationName) && $this->client_id){
                $client = Client::findOne($this->client_id);
                if($client){
                    $client->inn = $this->inn;
                    $client->name = $this->organizationName;
                    $client->save(false);
                }
            }
        }

        if (parent::beforeSave($insert)) {



            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'role_id' => 'Роль',
            'role' => 'Табельный',
            'name' => 'ФИО',
            'phone' => 'Телефон',
            'access' => 'Доступ',
            'password_hash' => 'Зашифрованный пароль',
            'created_at' => 'Дата создания',
            'is_deletable' => 'Можно удалить или нельзя',
            'percent_salary' => 'Процент заплаты',
            'email' => 'Email',
            'percent' => 'Процент',
            'token' => 'API-токен',
            'mail_host' => "Хост почты",
            'mail_pass' => "Пароль от почты",
            'mail_port' => "Порт почты",
            'mail_encryption' => "Протокол почты",
            'client_id' => "Клиент",
            'daks_balance' => "Даксы",
            'post_address' => "Адрес почты",
            'inn' => \Yii::t('app', 'ИНН'),
            'organizationName' => \Yii::t('app', 'Наименование организации'),
            'group' => \Yii::t('app', 'Группа'),
            'date_of_birth' => 'Дата рождения',
            'dolzhnost' => 'Должность'

        ];
    }

    /**
     * @return array
     */
    public static function roleLabels()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
        ];
    }

    public static function percentLabels()
    {
        return [
            self::PERCENT_CLASSIC => 'Классик',
            self::PERCENT_DYNAMIC => 'Динамик',
            self::PERCENT_PERCENT => 'Процентный',
            self::PERCENT_PERCENT_HALF => 'Проценты 50/50',
            self::PERCENT_DISPATCH => 'Диспетчер',
        ];
    }

    /**
    * @param string $action
    * @return bool
    */
    public function can($action)
    {
        if(Yii::$app->user->identity->role_id != null){
        //    $role = Role::findOne(Yii::$app->user->identity->role_id);

            $role = Yii::$app->myCache->get('can_role');

            //print_r($role);
            if($role == null){
                //$role = Role::findOne(Yii::$app->user->identity->role_id);
                $roles = Yii::$app->myCache->get('roles');

                foreach($roles as $role){
                    if($role->id == Yii::$app->user->identity->role_id)
                    break;
                }

                //print_r($role); die;

                //echo Yii::$app->user->identity->role_id; die;

                //$role = $roles[Yii::$app->user->identity->role_id];
                //print_r($role); die;
                Yii::$app->myCache->set('can_role',$role);
            }


            if($role){
                if(isset($role->$action)){
                    return $role->$action == 1;
                }
            }
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['login' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Админ компании или нет
     * @return bool
     */
    public function isCompanyAdmin()
    {
        return $this->is_company_super_admin == 1;
    }




    
    
    
    
    
    
    
    
        /**
     * @inheritdoc
     */
    public function getPermmission()
    {
        return $this->stat_indet;
    }

    /**
     * @return string
     */
    public function getRealAvatarPath()
    {
        return 'img/nouser.png';
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public static function getManagerList()
    {
        $query = self::find()->andWhere(['role' => self::ROLE_MANAGER]);

        return ArrayHelper::map($query->all(), 'id', 'name');
    }
}
