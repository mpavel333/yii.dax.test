<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model Ticket */

$this->title = 'Тикет "'.$model->subject.'"';


$role = \app\models\Role::findOne(\Yii::$app->user->identity->role_id);

?>

<style>
    .messages-container {
        height: 65vh;
        overflow-y: scroll;
    }

    .messages-container .panel-body {
        border: 1px solid #cecece;
        background: #f5f5f5;
    }

    .messages-container .message-header {
        margin-bottom: 0;
    }
    
    .messages-container ul li {
        margin-bottom: 0;
    }
</style>



<div class="ticket-view">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="messages-container">
                        <?= $this->render('messages-container', [
                            'messages' => $messages,
                            'user' => $user,
                        ]); ?>
                    </div>
                    <div class="chat-controllers">
                        <?php $form = ActiveForm::begin(['id' => 'chat-form', 'method' => "POST", 'action' => ['ticket-message/send', 'ticket_id' => $model->id], 'options' => ['enctype' => 'multipart/form-data']]) ?>

                            <textarea class="form-control" name="text" id="message-text-fld" rows="3"></textarea>
                            <input type="file" multiple=""  id="message-file-fld">
                            <button class="btn btn-block btn-primary" type="submit" style="margin-top: 15px;">Отправить</button>

                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $model->subject ?></h5>
                    <ul class="card-list" style="height: 180px; overflow-y: scroll;">
                        <li>
                            <div class="item-title">Статус</div>
                            <div class="item-value"><?php

                            if($model->status == \app\models\Ticket::STATUS_AWAIT){
                                echo "<span class='label label-warning'>".\yii\helpers\ArrayHelper::getValue(\app\models\Ticket::statusLabels(), $model->status)."</span>";
                            } elseif($model->status == \app\models\Ticket::STATUS_AT_WORK){
                                echo "<span class='label label-info'>".\yii\helpers\ArrayHelper::getValue(\app\models\Ticket::statusLabels(), $model->status)."</span>";
                            } elseif($model->status == \app\models\Ticket::STATUS_DONE){
                                echo "<span class='label label-default'>".\yii\helpers\ArrayHelper::getValue(\app\models\Ticket::statusLabels(), $model->status)."</span>";
                            }
                            
                            ?></div>
                        </li>
                        <?php if($role->ticket_manager): ?>

                            <li>
                                <div class="item-title">Пользователь</div>
                                <div class="item-value"><?php
                                    echo \yii\helpers\ArrayHelper::getValue($model, 'user.name');
                                ?></div>
                            </li>

                        <?php endif; ?>
                        <li>
                            <div class="item-title">Дата и время</div>
                            <div class="item-value"><?php
                                echo \Yii::$app->formatter->asDate($model->create_at, 'php:d.m.Y H:i');
                            ?></div>
                        </li>

                        <?php if($role->ticket_manager && $model->status == \app\models\Ticket::STATUS_AT_WORK): ?>
                            <li>
                                <div class="item-value"><?= \yii\helpers\Html::a('Закрыть', ['ticket/close', 'id' => $model->id], ['class' => 'btn btn-block btn-sm btn-danger', 'onclick' => 'return confirm("Вы уверены что хотите закрыть данный тикет?");']) ?></div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

$script = <<< JS

window.loadMessages = function()
{
    var toDown = true;


    if(($('.messages-container')[0].scrollHeight - $('.messages-container')[0].scrollTop) > 1000){
        toDown = false;
    }

    $.ajax({
        url: "/ticket/load-messages?ticket_id={$model->id}",
        success: function(response){
            $(".messages-container").html(response.html);

            if(toDown){
                $('.messages-container').scrollTop($('.messages-container')[0].scrollHeight);
            }
        },
    });
}

$("#chat-form button").click(function(e){
    e.preventDefault();

    var url = $("#chat-form").attr('action');
    var method = $("#chat-form").attr('method');
    var text = $('#message-text-fld').val();

    var formData = new FormData();
    formData.append('text', text);
    $.each($("#message-file-fld")[0].files,function(key, input){
    	formData.append('file[]', input);
    });

    $("#message-text-fld").val(null);
    $("#message-file-fld").val(null);
    

    if(text){
        $.ajax({
            url: url,
            method: method,
            data: formData,
			cache: false,
			contentType: false,
			processData: false,
            // data: {
            //     "text": text,
            // },
            success: function(response){
                window.loadMessages();
            },
        });
    } else {
        alert("Заполните текст для отправки сообщения");
    }
});

$('.messages-container').scrollTop($('.messages-container')[0].scrollHeight);

setInterval(function(){
    window.loadMessages();
}, 2000);

JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>