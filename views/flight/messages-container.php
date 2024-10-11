<?php foreach($messages as $message): ?>
    <?php
        $panelClass = null;
        $headerLabel = null;
        if($message->user_id == \Yii::$app->user->getId()){
            $panelClass = "inverse";
            $headerLabel = "Вы";
        } else {
            $panelClass = "info";
            $headerLabel = $user->name;
        }

        if(time() - strtotime($message->create_at) < 86400){ // 86400 — 24 часа
            $headerLabel .= "<i>(".\Yii::$app->formatter->asRelativeTime($message->create_at).")</i>";
        } else {
            $headerLabel .= "<i>(".\Yii::$app->formatter->asDate($message->create_at, 'php:d.m.Y H:i').")</i>";
        }

        if($message->user_id == \Yii::$app->user->getId()){
            if($message->is_read){
                $headerLabel .= "<i class='text-success fa fa-check pull-right'></i>";
            } else {
                $headerLabel .= "<i class='text-white fa fa-check pull-right'></i>";
            }
        } else {
            if($message->is_read == false){
                $headerLabel .= "<span class='label label-danger pull-right'>НОВОЕ</span>";
            }
        }
    ?>

    <div class="panel panel-<?= $panelClass ?>">
        <div class="panel-heading">
            <h4 class="panel-title"><?= $headerLabel ?></h4>
        </div>
        <div class="panel-body">
            <p><?= \Yii::$app->formatter->asNText($message->text) ?></p>
        </div>
    </div>

<?php endforeach; ?>