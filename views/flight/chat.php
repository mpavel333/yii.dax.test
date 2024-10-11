<?php

use \yii\widgets\ActiveForm;

?>
<style>
    .messages-container .panel-body {
        border: 1px solid #cecece;
    }

    .messages-container .panel-body p {
        margin-bottom: 0;
    }

    .messages-container .panel-heading h4 i {
        margin-left: 10px;
    }

    .messages-container {
        max-height: 50vh;
        overflow-y: scroll;
    }

</style>

<div class="row">
    <div class="col-md-12">
        <div class="messages-container">
            <?= $this->render('messages-container', [
                'messages' => $messages,
                'user' => $user,
            ]); ?>
        </div>
        <div class="chat-controllers">
            <?php $form = ActiveForm::begin(['id' => 'chat-form', 'method' => "POST", 'action' => ['message/send', 'flight_id' => $model->id, 'user_id' => $user->id]]) ?>

                <textarea class="form-control" name="text" id="message-text-fld" rows="3"></textarea>
                <button class="btn btn-block btn-primary" type="submit">Отправить</button>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<?php

$script = <<< JS

window.loadMessages = function()
{
    var toDown = true;


    if(($('.messages-container')[0].scrollHeight - $('.messages-container')[0].scrollTop) > 500){
        toDown = false;
    }

    $.ajax({
        url: "/flight/load-messages?flight_id={$model->id}&user_id={$user->id}",
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

    $("#message-text-fld").val(null);

    if(text){
        $.ajax({
            url: url,
            method: method,
            data: {
                "text": text,
            },
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

$("#ajaxCrudModal").on("hidden.bs.modal", function () {
    $("#ajaxCrudModal .modal-body").html(null);
});


JS;

$this->registerJs($script, \yii\web\View::POS_READY);

?>