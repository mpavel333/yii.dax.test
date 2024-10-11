<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Class RoleBehavior
 * @package app\behaviors
 */
class RoleBehavior extends Behavior
{
    public $instanceQuery;

    /**
     * @var array
     */
    public $actions;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param Event $event
     * @throws ForbiddenHttpException
     * @return boolean
     */
    public function beforeAction($event)
    {
        $request = Yii::$app->request;
        $action = $event->action->id;



        if(isset($this->actions[$action])){
            $permission = $this->actions[$action];

            if(is_array($permission)){
                foreach ($permission as $per){
                    if(Yii::$app->user->identity->can($per) == true){

                        // Проверка на принадлежность к компании
                        if(Yii::$app->user->identity->isSuperAdmin() == false){
                            $id = null;
                            if($request->isPost){
                                if(isset($_POST['id'])){
                                    $id = $_POST['id'];                            
                                }
                                $queryParams = $request->queryParams;
                                if(isset($queryParams['id'])){
                                    $id = $queryParams['id'];
                                }
                            } elseif($request->isGet && isset($_GET['id'])){
                                $id = $_GET['id'];
                            }
                            
                            if($id){
                                $obj = $this->instanceQuery->where(['id' => $id])->one();
                                if($obj){
                                    if($obj->company_id != Yii::$app->user->identity->company_id){
                                        throw new ForbiddenHttpException('У вас не достаточно прав');
                                    }
                                }
                            }
                        }




                        return true;
                    }
                }
                throw new ForbiddenHttpException('У вас не достаточно прав');
            } else {
                if(Yii::$app->user->identity->can($permission) == false){
                    throw new ForbiddenHttpException('У вас не достаточно прав');
                }

                // Проверка на принадлежность к компании
                if(Yii::$app->user->identity->isSuperAdmin() == false){
                    $id = null;
                    if($request->isPost){
                        if(isset($_POST['id'])){
                            $id = $_POST['id'];                            
                        }
                        $queryParams = $request->queryParams;
                        if(isset($queryParams['id'])){
                            $id = $queryParams['id'];
                        }
                    } elseif($request->isGet && isset($_GET['id'])){
                        $id = $_GET['id'];
                    }

                    if($id){
                        $obj = $this->instanceQuery->where(['id' => $id])->one();
                        if($obj){
                            // if($obj->company_id != Yii::$app->user->identity->company_id){
                            //     throw new ForbiddenHttpException('У вас не достаточно прав');
                            // }
                        }
                    }
                }
            }
        }
        return true;
    }
}