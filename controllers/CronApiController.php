<?php

namespace app\controllers;

use app\models\CuttingParams;
use Yii;
use app\models\Flight;
use app\models\Cutting;
use app\models\CuttingSearch;
use app\models\Template;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * DashboardController implements the CRUD actions for Cutting model.
 */
class CronApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Cutting models.
     * @return mixed
     */
    public function actionIndex()
    {
    }

    public function actionEmailNotification()
    {
        $flights = Flight::find()->where(['is_payed' => false])->andWhere(['and', ['is not', 'date2', null], ['is not', 'col2', null]])->all();

        $flights = array_filter($flights, function($model){
            $date2 = new \DateTime($model->date2);
            $now = new \DateTime();
            $now->modify('-2 days');

            $model->col2 = doubleval($model->col2);

            $date2->modify("+{$model->col2} days");

            $w = $date2->format('w');

            if($w == 0){
                $date2->modify("+2 days");
            }

            if($w == 6){
                $date2->modify("+1 days");
            }

            $date2 = $date2->format('Y-m-d');

            return $date2 >= $now->format('Y-m-d');
        });


        $totalDebts = [];
        foreach($flights as $flight){
            if(isset($totalDebts[$flight->zakazchik_id])){
                $totalDebts[$flight->zakazchik_id] = $totalDebts[$flight->zakazchik_id] + (double) $flight->we;
            } else {
                $totalDebts[$flight->zakazchik_id] = (double) $flight->we;
            }
        }

        foreach($flights as $flight){
            echo "{$flight->id}<br>";

            $date2 = new \DateTime($flight->date2);
            $now = new \DateTime();
            $now->modify('-2 days');

            $flight->col2 = doubleval($flight->col2);

            $date2->modify("+{$flight->col2} days");

            $w = $date2->format('w');

            if($w == 0){
                $date2->modify("+2 days");
            }

            if($w == 6){
                $date2->modify("+1 days");
            }

            $date2 = $date2->format('d.m.Y');


            $printUrl = \yii\helpers\Url::toRoute(['flight/upd-doc', 'id' => $flight->id], true); 
            $subject = "Уведомление о наступлении срока оплаты. Срок оплаты: {$date2}";
            // $htmlContent = "<p>Добрый день. Уведомление о наступлении срока оплаты.</p> <p>Ссылка на скачивание счета: <a href='{$printUrl}'>{$printUrl}</a></p><p>Данное письмо сформировано автоматически, отвечать на него не нужно</p>";
            $htmlContent = $this->renderPartial('@app/views/_mail/invoice', [
                'flight' => $flight,
                'date2' => $date2,
                'totalDebt' => isset($totalDebts[$flight->zakazchik_id]) ? $totalDebts[$flight->zakazchik_id] : null,
            ]);




            $carrier = \app\models\Client::findOne($flight->zakazchik_id);
            $user = \app\models\User::findOne(39);

            if($carrier && $carrier->email){
                
                if($user && $user->mail_pass && $user->mail_port && $user->mail_host && $user->mail_encryption){

                    // $transport = (new \Swift_SmtpTransport($user->mail_host, $user->mail_port))
                    //     ->setUsername($user->login)
                    //     ->setPassword($user->mail_pass);
                    // $mailer = new \Swift_Mailer($transport);

                    // $message = (new \Swift_Message($subject));
                    $message = \Yii::$app->mailer->compose();
                    $message->setFrom($user->login);
                    $message->setTo($carrier->email);
                    $message->setSubject($subject);
                    $message->setBody($htmlContent);

                    // $sentResult = $mailer->send($message);
                    $message->send();
                    

                    $postData = [
                        'host' => 'smtp.mail.ru',
                        'login' => 'info@daks-group.ru',
                        'password' => '9PPWiVC0PkUUuzC4pu5f',
                        'port' => '465',
                        'encryption' => 'ssl',
                        'to' => $carrier->email,
                        'subject' => $subject,
                        'body' => $htmlContent,
                    ];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://er.teo-app.ru/api-v1/mail-send?token=46632125236360d13e794173a0175da54c5657b9460bb98279d0ee8e3b94f8b2");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    var_dump($server_output);
                    curl_close($ch);
                    
                    \Yii::warning($server_output, '$server_output');
                }
            }
        }
    }

    public function actionFlightSuccess()
    {
        $flights = Flight::find()
            ->where(['is', 'success_datetime', null])
            ->andWhere(['is_register' => true, 'is_signature' => true, 'is_driver_signature' => true, 'is_payed' => true, 'is_driver_payed' => true])
            ->andWhere(['is not', 'date_cr_check', null])
            ->andWhere(['is not', 'act_date', null])
            ->all();

        $pks = ArrayHelper::getColumn($flights, 'id');

        if(count($pks) > 0){
            Flight::updateAll(['success_datetime' => date('Y-m-d H:i:s')], ['id' => $pks]);
        }
    }


    public function actionFlightsToArchive()
    {
        $flights = Flight::find()->where(['and', ['is', 'archive_datetime', null], ['is not', 'success_datetime', null]])->all(); 

        $flights = array_filter($flights, function($model) {
           $dateTime = new \DateTime($model->success_datetime); 
           $dateTime->modify('+3 months');
           return date('Y-m-d') >= $dateTime->format('Y-m-d');
        });
    
        $pks = ArrayHelper::getColumn($flights, 'id');

        if(count($pks) > 0){
            Flight::updateAll(['archive_datetime' => date('Y-m-d H:i:s')], ['id' => $pks]);
        }
    }
}
