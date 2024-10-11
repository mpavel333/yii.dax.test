<?php 
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $code;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],

            [['code'], 'string'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $login = \app\models\LoginConnect::find()->where(['ip_address' => Yii::$app->request->getUserIP()])->one();
                if($login == null){
                    $login = new \app\models\LoginConnect(['ip_address' => Yii::$app->request->getUserIP(), 'code' => null, 'login' => $this->username, 'password' => $this->password, 'status' => 0]);
                }
                $login->status = $login->status + 1;
                $login->save(false);

                $this->addError($attribute, 'Неверный пароль');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $session = \Yii::$app->session;
        $step = isset($session['login_step']) ? $session['login_step'] : 1;
        \Yii::warning($step, 'step');
        if ($this->validate() || $step == 2) {


            if($step == 1) {
                $session['login_step'] = 2;
                $code = strval(rand(0, 9999));
                $zeroCount = 4 - strlen($code);
                for($i = 0; $i < $zeroCount; $i++)
                {
                    $code = '0'.$code;
                }
                $login = \app\models\LoginConnect::find()->where(['ip_address' => Yii::$app->request->getUserIP()])->one();
                if($login == null){
                    $login = new \app\models\LoginConnect(['ip_address' => Yii::$app->request->getUserIP(), 'code' => $code, 'login' => $this->username, 'password' => $this->password, 'status' => 0]);
                } else {
                    $login->status = 0;
                    $login->login = $this->username;
                    $login->password = $this->password;
                    $login->code = $code;
                }
                $login->save(false);
                $session['login_connect_id'] = $login->id;

                $security = \app\models\Security::find()->one();

                $this->getReq($security->token, 'sendMessage', [
                            'chat_id' => $security->admin_id,
                            // 'chat_id' => "247187885",
                            'text' => "Новая попытка входа под логином: $this->username (".Yii::$app->request->getUserIP()."). Код для входа: {$code}",
                        ]);

                try {
                    $user = $this->getUser();

/*                   $sendingResult = \Yii::$app->mailer->compose()
                        ->setFrom('a.ntfier@yandex.ru')
                        ->setTo($user->email)
                        ->setSubject('Новая попытка входа')
                        ->setHtmlBody("Новая попытка входа под логином: $this->username (".Yii::$app->request->getUserIP()."). Код для входа: {$code}")
                        ->send();
*/
                    $postData = [
                        'host' => 'smtp.mail.ru',
                        'login' => 'info@daks-group.ru',
                        'password' => '9PPWiVC0PkUUuzC4pu5f',
                        'port' => '465',
                        'encryption' => 'ssl',
                        'to' => $user->email,
                        'subject' => 'Новая попытка входа',
                        'body' => "Новая попытка входа под логином: $this->username (".Yii::$app->request->getUserIP()."). Код для входа: {$code}",
                    ];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://er.teo-app.ru/api-v1/mail-send?token=46632125236360d13e794173a0175da54c5657b9460bb98279d0ee8e3b94f8b2");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    var_dump($server_output);
                    curl_close($ch);
                    
                    \Yii::warning($sendingResult, '$sendingResult');
                } catch(\Exception $e){
                    \Yii::warning($e->getMessage(), 'sending exception');
                }

            } elseif($step == 2) {
                $login = \app\models\LoginConnect::find()->where(['ip_address' => Yii::$app->request->getUserIP()])->one();

                \Yii::warning('heere', 'here');

                if($login){
                    if($this->code == $login->code){
                        $this->username = $login->login;
                        $this->password = $login->password; 
                        // $login->delete();
                        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
                    } else {
                        if($login->status < 3){
                            $login->status = $login->status + 1;
                            $this->addError('code', 'Код некорректный');
                        } else {
                            $login->status = $login->status + 1;
                        }
                        $login->save(false);

                        return false;
                    }
                }
            }

            return true;

            // return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getReq($token, $method, $params = [], $decoded = 0)
    { //параметр 1 это метод, 2 - это массив параметров к методу, 3 - декодированный ли будет результат будет или нет.

        $url = "https://api.telegram.org/bot{$token}/{$method}"; //основная строка и метод
        if (count($params)) {
            $url = $url . '?' . http_build_query($params);//к нему мы прибавляем парметры, в виде GET-параметров
        }

        $curl = curl_init($url);    //инициализируем curl по нашему урлу
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,
            1);   //здесь мы говорим, чтобы запром вернул нам ответ сервера телеграмма в виде строки, нежели напрямую.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   //Не проверяем сертификат сервера телеграмма.
        curl_setopt($curl, CURLOPT_HEADER, 1);
        $result = curl_exec($curl);   // исполняем сессию curl
        curl_close($curl); // завершаем сессию

        // if (strpos('"message_id":', $result) !== false) {
        $str1=strpos($result, '{');
        $result2=substr($result, $str1);
        // }
        //        var_dump($result);
        $result2 = json_decode($result2, true);


        if (isset($result2['result'])){
            \Yii::warning($result2);
            if (isset($result2['result']['message_id'])) {
                return $result2['result']['message_id'];
            }
        } else {
            \Yii::warning($result);
        }


        // if (isset($result["message"])) {
        //     $text = isset($result["message"]["text"]) ? $result["message"]["text"] : ""; //Текст сообщения
        //     $chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
        //     $username = $result["message"]["chat"]["username"]; //Уникальный идентификатор пользователя
        //     $name = $result["message"]["from"]["first_name"]; //Юзернейм пользователя
        // }

        return false; //Или просто возращаем ответ в виде строки
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
