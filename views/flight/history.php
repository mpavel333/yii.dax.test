<?php

use app\models\Flight;
use app\models\FlightHistory;
use yii\helpers\ArrayHelper;

$flight = new Flight();

?>

<style>
    .table-history p {
        margin-bottom: 0;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <table class="table table-history table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Дата и время</th>
                    <th>Изменения</th>
                    <th>Пользователь</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach(\app\models\FlightHistory::find()->where(['flight_id' => $id])->orderBy('create_at desc')->all() as $history): ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;"><?= \Yii::$app->formatter->asDate($history->create_at, 'php:d.m.Y H:i') ?></td>
                        <td><?php
                            $data = json_decode($history->data, true);

                            foreach($data as $attribute => $value){
                                echo "<p><b>".$flight->getAttributeLabel($attribute)."</b>: {$value}</p>";
                            }
                        ?></td>
                        <td style="vertical-align: middle; text-align: center;"><?= ArrayHelper::getValue($history, 'user.name') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>