<?php

use app\admintheme\widgets\Menu;
use app\models\Flight;

$searchModel = new \app\models\FlightSearch(['enableControllerCheck' => false]);
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
$dataProvider->pagination = false;

if(Yii::$app->user->identity->isSuperAdmin()){
    $users = \app\models\User::find()->where(['and', ['!=', 'role', null], ['role_id' => 8]])->all();
    $users = array_filter($users, function($user){
        $role = explode(',', $user->role);
        return in_array(\Yii::$app->user->getId(), $role);
    });
    $usersPks = \yii\helpers\ArrayHelper::getColumn($users, 'id');

    $dataProvider->query->andWhere(['flight.user_id' => null, 'is_order' => true]);
} else {
    $users = \app\models\User::find()->where(['role_id' => 8])->all();
    $users = array_filter($users, function($user){
        if($user->role == null){
            return true;
        }
        $role = explode(',', $user->role);
        $userRole = explode(',', \Yii::$app->user->identity->role);


        foreach ($role as $r) {
            foreach ($userRole as $userR) {
                if($r == $userR){
                    return true;
                }
            }
        }

        return false;
    });

    if(count($users) > 0){
        $usersPks = \yii\helpers\ArrayHelper::getColumn($users, 'id');

        $dataProvider->query->andWhere(['created_by' => $usersPks, 'is_order' => true]);
    } else {
        $dataProvider->query->andWhere(['created_by' => null, 'is_order' => true]);
    }
}
$dataProvider->query->andWhere(['status' => null]);
$flightCount = count($dataProvider->models);


$searchModel = new \app\models\FlightSearch(['enableControllerCheck' => false]);
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
$dataProvider->pagination = false;

if(Yii::$app->user->identity->isSuperAdmin()){
    $users = \app\models\User::find()->where(['and', ['!=', 'role', null], ['role_id' => 8]])->all();
    $users = array_filter($users, function($user){
        $role = explode(',', $user->role);
        return in_array(\Yii::$app->user->getId(), $role);
    });
    $usersPks = \yii\helpers\ArrayHelper::getColumn($users, 'id');

    $dataProvider->query->andWhere(['flight.user_id' => null, 'flight.is_lawyer' => true]);
} else {
    $users = \app\models\User::find()->where(['role_id' => 8])->all();
    $users = array_filter($users, function($user){
        if($user->role == null){
            return true;
        }
        $role = explode(',', $user->role);
        $userRole = explode(',', \Yii::$app->user->identity->role);


        foreach ($role as $r) {
            foreach ($userRole as $userR) {
                if($r == $userR){
                    return true;
                }
            }
        }

        return false;
    });

    if(count($users) > 0){
        $usersPks = \yii\helpers\ArrayHelper::getColumn($users, 'id');

        $dataProvider->query->andWhere(['created_by' => $usersPks, 'flight.is_lawyer' => true]);
    } else {
        $dataProvider->query->andWhere(['created_by' => null, 'flight.is_lawyer' => true]);
    }

    $dataProvider->query->andWhere(['!=', 'status', Flight::STATUS_WORKING_LAWYER]);
}
$dataProvider->query->andWhere(['status' => null]);
$lawyerFlightCount = count($dataProvider->models);

if(\Yii::$app->user->identity->can('calls')){
    if(\Yii::$app->user->identity->isSuperAdmin()){
        $callsCount = \app\models\Call::find()->andWhere(['status' => 1])->andWhere(['or', ['is', 'result', null], ['result' => '']])->count();
    } else {
        $callsCount = \app\models\Call::find()->andWhere(['status' => 1])->andWhere(['or', ['is', 'result', null], ['result' => '']])->count();
    }
    $coldCallsLabel = $callsCount > 0 ? Yii::t('app', 'Холодные звонки')." <span style=\"display: inline-block;color: #fff;background: #E73751;border-radius: 5px;padding: 0px 2px;line-height: 16px;font-size: 11px;\">{$callsCount}</span>" : Yii::t('app', 'Холодные звонки');
    // $coldCallsLabelHeader = $callsCount > 0 ? Yii::t('app', 'Работа с клиентом')." <span style=\"display: inline-block; color: #fff; background: #ff5b57; border-radius: 5px; padding: 0 4px;\">{$callsCount}</span>" : Yii::t('app', 'Работа с клиентом');
    $coldCallsLabelHeader = $callsCount > 0 ? Yii::t('app', 'Работа с клиентом')." <span style=\"display: inline-block;color: #fff;background: #E73751;border-radius: 5px;padding: 0px 2px;line-height: 16px;font-size: 11px;\">{$callsCount}</span>" : Yii::t('app', 'Работа с клиентом');
} else {
    $callsCount = 0;
    $coldCallsLabel = 0;
    $coldCallsLabelHeader = 0;
}

$ticketMessages = \app\models\TicketMessage::find()->andWhere(['!=', 'user_id', \Yii::$app->user->getId()])->andWhere(['is', 'is_read', null])->count();
$messages = \app\models\Message::find()->where(['user_to_id' => \Yii::$app->user->getId()])->andWhere(['is', 'is_read', null])->count();

?>



<div id="sidebar" class="sidebar sidebar-grid">
    <?php if (Yii::$app->user->isGuest == false): ?>        <?php        try {
            echo Menu::widget(
                [
                    'options' => ['class' => 'nav'],
                    'items' => [
                        ['label' => Yii::t('app', 'Рабочий стол'), 'icon' => 'house', 'url' => ['/dashboard'], 'visible' => Yii::$app->user->identity->can('dashboard')],
                    

                        ['label' => Yii::t('app', 'Реквизиты'), 'icon' => 'docs', 'url' => ['/requisite'], 'visible' => Yii::$app->user->identity->can('requisite_view')],


                        ['label' => Yii::t('app', 'Организации'), 'icon' => 'building', 'url' => "#", 'options' => ['class' => 'has-sub'], 'visible' => Yii::$app->user->identity->can('client_view'),
                            'items' => [
                                ['label' => Yii::t('app', 'Заказчик'), 'url' => ['/client']],
                                ['label' => Yii::t('app', 'Перевозчик'), 'url' => ['/client-driver']],
                            ],
                        ],


                        ['label' => Yii::t('app', 'Водители'), 'icon' => 'car', 'url' => ['/driver'], 'visible' => Yii::$app->user->identity->can('driver_view')],


                        ['label' => Yii::t('app', 'Металлика'), 'icon' => 'path', 'url' => ['/metal'], 'visible' => Yii::$app->user->identity->can('metal_view')],
   

                    ['label' => Yii::t('app', 'Рейсы'), 'icon' => 'path', 'url' => '#', 'options' => ['class' => 'has-sub'], 'visible' => Yii::$app->user->identity->can('flight_view'),
                        'items' => [
                            ['label' => Yii::t('app', 'Актуальные'), 'url' => ['/flight']],
                            ['label' => Yii::t('app', 'В работе'), 'encode' => false, 'url' => ['/work']],
                            ['label' => Yii::t('app', 'Группа'), 'url' => ['/flight-group'], 'visible' => Yii::$app->user->identity->can('flight_group_table')],
                            ['label' => Yii::t('app', 'Архив'), 'url' => ['/archive']],
                        ],
                ],

                        // ['label' => Yii::t('app', 'Рейсы'), 'encode' => false, 'icon' => 'fa fa-flickr', 'url' => ['/flight'], 'visible' => Yii::$app->user->identity->can('flight_view')],
                        

                        ['label' => Yii::t('app', 'Заявки').($flightCount > 0 ? ' <span style="display: inline-block; color: #fff; background: #ff5b57; border-radius: 5px; padding: 0 4px;">'.$flightCount.'</span>' : ''), 'afterIcon' => ($flightCount > 0 ? ' <span class="item-after-icon" style="display: inline-block; color: #fff; background: #ff5b57; border-radius: 5px; padding: 0 4px;">'.$flightCount.'</span>' : ''), 'encode' => false, 'icon' => 'flag', 'url' => ['/order'], 'visible' => Yii::$app->user->identity->can('flight_orders_show')],
                        
                        // ['label' => Yii::t('app', 'Почта'), 'icon' => 'fa fa-envelope', 'url' => ['/mail'], 'visible' => Yii::$app->user->identity->can('mail_view')],

                    ['label' => Yii::t('app', 'Почта'), 'icon' => 'letter', 'url' => '#', 'options' => ['class' => 'has-sub'], 'visible' => Yii::$app->user->identity->can('mail_view'),
                        'items' => [
                            ['label' => Yii::t('app', 'Исходящие'), 'url' => ['/mail']],
                            ['label' => Yii::t('app', 'Входящие'), 'url' => ['/mail-received']],
                        ],
                ],

                ['label' => \Yii::t('app', 'Работа с клиентом'), 'number' => ($callsCount > 0 ? " <span style=\"display: inline-block;color: #fff;background: #E73751;border-radius: 5px;padding: 0px 2px;line-height: 16px;font-size: 11px;\">{$callsCount}</span>" : ''), 'icon' => 'client', 'url' => '#', 'options' => ['class' => 'has-sub'], 'encode' => false, 'visible' => \Yii::$app->user->identity->can('client_work'),
                        'items' => [
                                                        ['label' => Yii::t('app', 'База'), 'icon' => 'fa  fa-phone', 'url' => ['/call']],
                                                        ['label' => $coldCallsLabel, 'icon' => 'fa  fa-phone', 'url' => ['/call-cold'], 'encode' => false],
                                                        ['label' => Yii::t('app', 'Мои заказчики'), 'icon' => 'fa  fa-phone', 'url' => ['/call-my']],
                                                        ['label' => Yii::t('app', 'Архив'), 'icon' => 'fa  fa-phone', 'url' => ['/call-archive']],
                                                    ],


                ],


                    ['label' => Yii::t('app', 'Настройки'), 'icon' => 'cog', 'url' => '#', 'options' => ['class' => 'has-sub'], 'visible' => Yii::$app->user->identity->can('settings') || Yii::$app->user->identity->can('users'),
                        'items' => [
                                                        ['label' => Yii::t('app', 'Пользователи'), 'icon' => 'fa  fa-user-o', 'url' => ['/user'], 'visible' => Yii::$app->user->identity->can('settings') || Yii::$app->user->identity->can('users')],
                                                        ['label' => Yii::t('app', 'Пользователи клиента'), 'icon' => 'fa  fa-user-o', 'url' => ['/user-client'], 'visible' => Yii::$app->user->identity->can('settings')],
                                                        ['label' => Yii::t('app', 'Пользователи водители'), 'icon' => 'fa  fa-user-o', 'url' => ['/user-driver'], 'visible' => Yii::$app->user->identity->can('settings')],
                            ['label' => Yii::t('app', 'Роли'), 'icon' => 'fa  fa-star', 'url' => ['/role'], 'visible' => Yii::$app->user->identity->can('settings')],
                            // ['label' => Yii::t('app', 'Отчеты'), 'icon' => 'fa  fa-star', 'url' => ['/report']],
                            // ['label' => Yii::t('app', 'Поля отчета'), 'icon' => 'fa  fa-star', 'url' => ['/report-column']],
                                                        ['label' => Yii::t('app', 'Шаблоны рейсов'), 'icon' => 'fa fa-file-o', 'url' => ['/template'], 'visible' => Yii::$app->user->identity->can('settings')],
                                                        ['label' => Yii::t('app', 'Шаблоны автопарка'), 'icon' => 'fa fa-file-o', 'url' => ['/template-car'], 'visible' => Yii::$app->user->identity->can('settings')],
                                                        ['label' => Yii::t('app', 'Шаблоны клиента'), 'icon' => 'fa fa-file-o', 'url' => ['/template-client'], 'visible' => Yii::$app->user->identity->can('settings')],
                                                        ['label' => Yii::t('app', 'Шаблоны металлика'), 'icon' => 'fa fa-file-o', 'url' => ['/template-metal'], 'visible' => Yii::$app->user->identity->can('settings')],
                        ['label' => Yii::t('app', 'Зарплата'), 'icon' => 'fa fa-rub', 'url' => ['/salary'], 'visible' => Yii::$app->user->identity->can('settings')],
                    ['label' => Yii::t('app', 'Праздники'), 'icon' => 'fa fa-gift', 'url' => ['/holiday'], 'visible' => Yii::$app->user->identity->can('holiday_view')],
                    ['label' => Yii::t('app', 'Индексы заявок'), 'icon' => 'fa fa-cog', 'url' => ['/setting'], 'visible' => Yii::$app->user->identity->isSuperAdmin()],
                        
                                                        
                                                    ],


                ],

                    ['label' => Yii::t('app', 'Входы'), 'icon' => 'exit', 'url' => ['login-connect/index'], 'visible' => \Yii::$app->user->identity->can('login')],
                    ['label' => Yii::t('app', 'Телеграм'), 'icon' => 'telegram', 'url' => ['security/index'], 'visible' => \Yii::$app->user->identity->can('security_table')],
                    ['label' => Yii::t('app', 'Чаты').($messages > 0 ? ' <span class="item-after-icon" style="display: inline-block !important; color: #fff; background: #ff5b57; border-radius: 5px; padding: 0 4px;">'.$messages.'</span>' : ''), 'icon' => 'telegram', 'url' => ['/message/index'], 'encode' => false],
                    

                    ['label' => Yii::t('app', 'Юристы').($lawyerFlightCount > 0 ? ' <span style="display: inline-block; color: #fff; background: #ff5b57; border-radius: 5px; padding: 0 4px;">'.$lawyerFlightCount.'</span>' : ''), 'afterIcon' => ($lawyerFlightCount > 0 ? ' <span class="item-after-icon" style="display: inline-block; color: #fff; background: #ff5b57; border-radius: 5px; padding: 0 4px;">'.$lawyerFlightCount.'</span>' : ''), 'icon' => 'docs', 'url' => ['/lawyer'], 'encode' => false, 'visible' => Yii::$app->user->identity->can('lawyer'),
                        'items' => [
                            ['label' => Yii::t('app', 'Договора'), 'url' => ['/lawyer/contracts']],
                        ]
                    ],
                    
                    
                    ['label' => Yii::t('app', 'Безопасность'), 'icon' => 'shield', 'url' => 'https://check.otk.su/order/create', 'visible' => Yii::$app->user->identity->can('security')],


                    // ['label' => Yii::t('app', 'Автопарк'), 'icon' => 'fa fa-automobile', 'url' => ['/car'], 'visible' => Yii::$app->user->identity->can('car')],

                    ['label' => Yii::t('app', 'Автопарк'), 'icon' => 'auto-park', 'url' => '#', 'options' => ['class' => 'has-sub'], 'visible' => Yii::$app->user->identity->can('car') || Yii::$app->user->identity->can('rent_car'),
                        'items' => [
                            ['label' => Yii::t('app', 'Собственный'), 'url' => ['/car'], 'visible' => Yii::$app->user->identity->can('car')],
                            ['label' => Yii::t('app', 'Арендованный'), 'url' => ['/rent-car'], 'visible' => Yii::$app->user->identity->can('rent_car')],
                            ['label' => Yii::t('app', 'План отчет'), 'url' => ['/plan-report'], 'visible' => 1], //Yii::$app->user->identity->can('plan_report')],
                        ],
                    ],

                    ['label' => Yii::t('app', 'Структура компании').($ticketMessages > 0 ? ' <span class="item-after-icon" style="display: inline-block !important; color: #fff; background: #ff5b57; border-radius: 5px; padding: 0 4px;">'.$ticketMessages.'</span>' : ''), 'encode' => false, 'icon' => 'shield', 'url' => ['/structure'],'visible' =>Yii::$app->user->identity->can('structure_view')],

                    ['label' => Yii::t('app', 'Техподдержка').($ticketMessages > 0 ? ' <span class="item-after-icon" style="display: inline-block !important; color: #fff; background: #ff5b57; border-radius: 5px; padding: 0 4px;">'.$ticketMessages.'</span>' : ''), 'encode' => false, 'icon' => 'shield', 'url' => ['/ticket/index']],
                ],
                ]
            );
        } catch (Exception $e) {
            Yii::error($e->getMessage(), '_error');
            echo $e->getMessage();
        }
        ?>
    <?php endif; ?>
</div>