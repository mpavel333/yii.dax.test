<?php 
namespace app\controllers;

use app\filters\BanFilter;
use app\models\AdminFiles;
use app\models\Candidate;
use app\models\CandidateAnswer;
use app\models\CandidateAnswerSearch;
use app\models\forms\AvatarForm;
use app\models\Settings;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\forms\ResetPasswordForm;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserDriverController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'view', 'profile', 'check-candidate-info'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['role_id' => 12]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     *
     */
    public function actionUploadAvatar()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $model = new AvatarForm();

        if ($model->load($request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
                return ['success' => 1, 'path' => $model->path];
            }
            return ['success' => 0];
        } else {
            return ['success' => 0];
        }
    }

    /**
     * Updates an existing User model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->scenario = User::SCENARIO_EDIT;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Редактирование пользователя водителя #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Отменить',
                            ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Сохранить', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                if ($model->load($request->post()) && $model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'forceClose' => true,
                    ];
                } else {
                    return [
                        'title' => "Редактирование пользователя водителя #" . $id,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Отменить',
                                ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Сохранить', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "пользователь #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cancel',
                        ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Update', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Экшен для изменения логина прямо из таблицы. С помощью плагина Editable
     * @param $id
     * @return array
     */
    public function actionEditLogin($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = User::find()->where(['id' => $id])->one();
        $model->scenario = User::SCENARIO_EDIT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['output' => $model->login, 'message' => null];
        } else {
            $errors = $model->getFirstErrors();
            $error = reset($errors);
            return ['output' => $model->login, 'message' => $error];
        }
    }


    /**
     * Creates a new User model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new User();
        $model->role_id = 12;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Создание пользователя водителя",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Отменить',
                            ['class' => 'btn btn-white pull-left btn-sm', 'data-dismiss' => "modal"]) .
                        Html::button('Сохранить', ['class' => 'btn btn-primary btn-sm', 'type' => "submit"])

                ];
            } else {
                if ($model->load($request->post()) && $model->save()) {

                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Создание пользователя водителя",
                        'content' => '<span class="text-success">Пользователь успешно создан</span>',
                        'footer' => Html::button('ОК',
                                ['class' => 'btn btn-default btn-sm pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Создать еще', ['create'],
                                ['class' => 'btn btn-primary btn-sm', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => "Создание пользователя водителя",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Отменить',
                                ['class' => 'btn btn-default btn-sm pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Сохранить', ['class' => 'btn btn-primary btn-sm', 'type' => "submit"])

                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

    }

    public function actionYandexToken()
    {
        if(\Yii::$app->user->identity->isSuperAdmin() == false){
            throw new \yii\web\ForbiddenHttpException('Доступ запрещен');
        }
        $request = \Yii::$app->request;

        $model = \app\models\YandexToken::find()->one();

        if($model == null){
            $model = new \app\models\YandexToken();
            $model->save(false);
        }

        if ($model->load($request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', 'Изменения успешно сохранены');
        } 

        return $this->render('yandex-token', [
            'model' => $model,
        ]);
    }


    /**
     * Изменяет пароль у конкретного пользователя
     * @param $id
     * @return array
     */
    public function actionResetPassword($id)
    {
        $request = Yii::$app->request;
        $model = new ResetPasswordForm(['uid' => $id]);
        $model->scenario = ResetPasswordForm::SCENARIO_BY_ADMIN;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Сменить пароль",
                    'content' => $this->renderAjax('reset-password-form', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancel',
                            ['class' => 'btn btn-white pull-left btn-sm', 'data-dismiss' => "modal"]) .
                        Html::button('Update', ['class' => 'btn btn-primary btn-sm', 'type' => "submit"])

                ];
            } else {
                if ($model->load($request->post()) && $model->resetPassword()) {
                    return [
                        'title' => "Сменить пароль",
                        'content' => '<span class="text-success">Пароль успешно изменен</span>',
                        'footer' => Html::button('Закрыть',
                            ['class' => 'btn btn-white btn-sm', 'data-dismiss' => "modal"]),
                    ];
                } else {
                    return [
                        'title' => "Сменить пароль",
                        'content' => $this->renderAjax('reset-password-form', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cancel',
                                ['class' => 'btn btn-white pull-left btn-sm', 'data-dismiss' => "modal"]) .
                            Html::button('Update', ['class' => 'btn btn-primary btn-sm', 'type' => "submit"])

                    ];
                }
            }
        }
    }

    /**
     * Delete an existing User model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $deleted = $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($deleted == false) {
                return ['forceClose' => true, 'forceReload' => '#report-messages-pjax'];
            }

            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];

        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    /**
     * Delete multiple existing User model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ['forceClose' => true, 'forceReload' => '#report-messages-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }

    }


    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest == false){
            $role = \app\models\Role::findOne(Yii::$app->user->identity->role_id);
            if($role){
                if($role->settings == false){
                    throw new \yii\web\ForbiddenHttpException('Нет доступа');
                }
            } else {
                throw new \yii\web\ForbiddenHttpException('Нет доступа');
            }

        }


        if (!parent::beforeAction($action)) {
            return false;
        }


        return true;
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемой страницы не существует.');
        }
    }
}
