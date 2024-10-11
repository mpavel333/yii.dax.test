<?php 
namespace app\modules\mobile\controllers;

use app\models\Company;
use app\models\MobileUser;
use app\models\Chat;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use app\behaviors\RoleBehavior;

class ChatController extends Controller
{
    public $rawData;

    private $user;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new \app\models\UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $page = isset($_GET['p']) ? $_GET['p'] : 0;

        $dataProvider->pagination->page = $page;

        $dataProvider->query->andFilterWhere(['!=', "id", Yii::$app->user->identity->id]);

        $models = $dataProvider->models;

        array_walk($models, function(&$model){
            $model->count_new_messages = \app\models\ChatHistory::find()->where(["sender_id" => $model->id, "user_id" => Yii::$app->user->identity->id, "view" => 0])->count();
            if($model->count_new_messages == 0){
                $model->count_new_messages = null;
            }
            $lastMessage = \app\models\ChatHistory::find()->where([
                    'or',
                    ["sender_id" => $model->id, "user_id" => Yii::$app->user->identity->id],
                    ["sender_id" => Yii::$app->user->identity->id, "user_id" => $model->id],
                ])->orderBy('id desc')->one();
            $lastMessageText = null;
            $dataFinMessage = null;
            if($lastMessage){
                $lastMessageText = $lastMessage->text;
                $dataFinMessage = $lastMessage->created_at;
            }
            $model->last_message = $lastMessageText;
            $model->data_fin_message = $dataFinMessage;
        });

        usort($models, function($a, $b){
            return $a['data_fin_message'] < $b['data_fin_message'];
        });

        $result["data"] = $models;
        return $result;
    }

    /**
     * @return mixed
     */
    public function actionHistory($user_id)
    {
        
        $searchModel = new \app\models\ChatHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        $page = isset($_GET['p']) ? $_GET['p'] : 0;

        $dataProvider->pagination->page = $page;

        $dataProvider->query->andFilterWhere([
            'or',
            ['and', ['=', 'sender_id', Yii::$app->user->identity->id], ['=', 'user_id', $user_id]],
            ['and', ['=', 'sender_id', $user_id], ['=', 'user_id', Yii::$app->user->identity->id]],
        ]);

        $models = $dataProvider->models;


        array_walk($models, function(&$model){
            if($model->user_id == Yii::$app->user->identity->id){
                $model->view = true;
                $model->save(false);
            }
        });

        $result["data"] = $models;
        return $result;
    }

    /**
     * @return mixed
     */
    public function actionSend()
    {
        $request = Yii::$app->request;
        $data = $this->rawData;
        $model = new \app\models\ChatHistory();
        $model->sender_id = Yii::$app->user->identity->id;
        $model->user_id = $data["user_id"];
        $model->text = $data["text"];
        $model->created_at = date("Y-m-d H:m:s");
        $model->view = false;

        $model->save(false);

        $user = \app\models\User::findOne($data["user_id"]);
        if ($user->push_token != null) {
            $this->push($user->push_token, "Новое сообщение", $data["text"]);
        }


        return ['result' => true];
    }

    /**
     * @return mixed
     */
    public function actionSetToken($token_push)
    {
        $user = \app\models\User::findOne(Yii::$app->user->identity->id);
        $user->push_token = $token_push;
        $user->save(false);

        return ['result' => true];
    }

    /**
     * @return mixed
     */
    public function actionGetMyId()
    {
        return ['result' => Yii::$app->user->identity->id];
    }

    /**
     * @return mixed
     */
    public function Push($token_push, $title, $text)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $request_body = [
            'to' => $token_push,
            'notification' => [
                'title' => $title,
                'body' => $text,
            ],
        ];

        $fields = json_encode($request_body);

        $request_headers = [
            'Content-Type: application/json',
            'Authorization: key=AAAA-oIdCNA:APA91bH7Kdh8sAYgdZuPiMOLkCgImZ6T3bc9yj8P-31uJveVxl4U1CXgL7taoooUNmLWUzH6yAFBkX90szuIq1md71zTDKwKqCQdk7HQ6EW-DYOYRcbGjFW5ZMxhNjWhLt0tH4RfL7_S',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);
        // print_r($response);

    }

    public function beforeAction($action)
    {
        Yii::$app->controller->enableCsrfValidation = false;

        $content = file_get_contents('php://input');
        $this->rawData = json_decode($content, true);

        if($action->id != 'login' && $action->id != 'push')
        {
            $token = null;
            if(isset($_GET['token'])){
                $token = $_GET['token'];
            } elseif(isset($this->rawData['token'])){
                $token = $this->rawData['token'];
            }

            if($token == null){
                Yii::$app->response->format = Response::FORMAT_JSON;
                throw new \yii\web\BadRequestHttpException('Токен не указан');
            }

            $token = explode('||', $token);

            $user = \app\models\User::find()->where(['login' => $token[0], 'password_hash' => $token[1]])->one();

            if($user == null){
                Yii::$app->response->format = Response::FORMAT_JSON;
                throw new \yii\web\BadRequestHttpException('Неверный логин или пароль');
            }

            Yii::$app->user->login($user, 0);
        }

        return parent::beforeAction($action);
    }
}