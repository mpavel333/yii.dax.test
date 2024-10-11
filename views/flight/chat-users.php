<?php

use app\models\Message;
use app\models\Role;
use yii\helpers\Url;

?>

<style>

    .persons-list {
        list-style-type: none;
        padding-left: 0;
    }

    .persons-list li {
        width: 100%;
        background: red;
        padding: 14px 10px;
    }

    .persons-list li a, .persons-list li a:hover {
        text-decoration: none;
    }

    .persons-list li a .li-header {
        font-size: 25px;
    }

    .persons-list li a .li-label {
        font-size: 14px;
    }

    .persons-list li a .li-mark {
        display: inline-block;
        float: right;
        background: #e24834;
        margin-top: -42px;
        padding: 7px;
        border-radius: 100%;
        color: #fff;
        line-height: 1;
    }

    .persons-list li.li-primary {
        background: #bbdcfa;
        border: 1px solid #348fe2;
        border-radius: 10px;
    }
    .persons-list li.li-primary a {
        color: #348fe2;
    }
    
    .persons-list li.li-white {
        background: #fff;
        border: 1px solid #707478;
        border-radius: 10px;
    }
    .persons-list li.li-white a {
        color: #707478;
    }

    .persons-list li.li-warning {
        background: #faedd4;
        border: 1px solid #e2a534;
        border-radius: 10px;
    }
    .persons-list li.li-warning a {
        color: #e2a534;
    }

    .persons-list li {
        margin-bottom: 5px;
    }

</style>

<div class="row">
    <div class="col-md-12">
        <ul class="persons-list">
            <?php foreach($users as $user): ?>
                <?php
                    if($user->id == \Yii::$app->user->getId()){
                        continue;
                    }
                
                    $liClass = null;
                    $role = Role::findOne($user->role_id);
                    $personLabel = $role ? $role->name : "—";

                    if($model->created_by == $user->id){
                        $liClass = "li-primary";
                    } elseif($model->user_id == $user->id){
                        $liClass = "li-warning";
                    } else {
                        $liClass = 'li-white';
                    }
                ?>
                <li class="<?= $liClass ?>">
                    <a href="<?= Url::toRoute(['flight/chat', 'id' => $model->id, 'user' => $user->id]) ?>" role="modal-remote">
                        <div class="li-label"><?= $personLabel ?></div>
                        <div class="li-header"><i class="fa fa-weixin"></i> <?= $user->name ?></div>
                        <?php
                            $unreadCount = \app\models\Message::find()->where(['user_id' => $user->id, 'user_to_id' => \Yii::$app->user->getId()])->andWhere(['is', 'is_read', null])->count();
                        ?>
                        <?php if($unreadCount > 0): ?>
                            <div class="li-mark"><?= $unreadCount ?></div>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>