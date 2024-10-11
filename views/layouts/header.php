<?php
use yii\helpers\Html;
use app\models\Users;


$navbar_titles=[
    'dashboard' => ['title'=>'Рабочий стол','icon'=>'house.svg'],
    'requisite' => ['title'=>'Реквизиты','icon'=>'requisite.svg'],
    'client' => ['title'=>'Организации - Заказчик','icon'=>'client.svg'],
    'client-driver' => ['title'=>'Организации - Перевозчик','icon'=>'client.svg'],
    'driver' => ['title'=>'Водители','icon'=>'driver.svg'],
    'metal'  => ['title'=>'Рейсы - Металика','icon'=>'flight.svg'],
    'flight' => ['title'=>'Рейсы - Актуальные','icon'=>'flight.svg'],
    'work'   => ['title'=>'Рейсы - В работе','icon'=>'flight.svg'],
    'flight-group' => ['title'=>'Рейсы - Группа','icon'=>'flight.svg'],
    'archive' => ['title'=>'Рейсы - Архив','icon'=>'flight.svg'],
    'order' => ['title'=>'Заявки','icon'=>'order.svg'],
    'mail' => ['title'=>'Почта - Исходящие','icon'=>'mail.svg'],
    'mail-received' => ['title'=>'Почта - Входящие','icon'=>'mail.svg'],
    'call' => ['title'=>'Работа с клиентом - База','icon'=>'call.svg'],
    'call-cold' => ['title'=>'Работа с клиентом - Холодные звонки','icon'=>'call.svg'],
    'call-my' => ['title'=>'Работа с клиентом - Мои заказчики','icon'=>'call.svg'],
    'call-archive' => ['title'=>'Работа с клиентом - Архив','icon'=>'call.svg'],
    'user' => ['title'=>'Настройки - Пользователи','icon'=>'settings.svg'],
    'user-client' => ['title'=>'Настройки - Пользователи клиента','icon'=>'settings.svg'],
    'user-driver' => ['title'=>'Настройки - Пользователи водители','icon'=>'settings.svg'],
    'role' => ['title'=>'Настройки - Роли','icon'=>'settings.svg'],
    'template' => ['title'=>'Настройки - Шаблоны рейсов','icon'=>'settings.svg'],
    'template-car' => ['title'=>'Настройки - Шаблоны автопарка','icon'=>'settings.svg'],
    'template-client' => ['title'=>'Настройки - Шаблоны клиента','icon'=>'settings.svg'],
    'salary' => ['title'=>'Настройки - Зарплата','icon'=>'settings.svg'],
    'holiday' => ['title'=>'Настройки - Праздники','icon'=>'settings.svg'],
    'setting' => ['title'=>'Настройки - Индексы заявок','icon'=>'settings.svg'],
    'login-connect' => ['title'=>'Входы','icon'=>'login-connect.svg'],
    'security' => ['title'=>'Телеграм','icon'=>'security.svg'],
    'car' => ['title'=>'Автопарк - Собственный','icon'=>'car.svg'],
    'rent-car' => ['title'=>'Автопарк - Арендованный','icon'=>'car.svg'],
];

$navbar_title = '';
$navbar_icon = '';

if(isset($navbar_titles[Yii::$app->controller->id])):
    $navbar_title = $navbar_titles[Yii::$app->controller->id]['title'];
    $navbar_icon = '<img width="32" height="32" class="house" src="/img/svg/'.$navbar_titles[Yii::$app->controller->id]['icon'].'">';
endif;


?>

<div id="header" class="header navbar navbar-default navbar-fixed-top">
            <!-- begin container-fluid -->
            <div class="container-fluid">
                <!-- begin mobile sidebar expand / collapse button -->
                <div class="navbar-header">
                    <a href="<?=Yii::$app->homeUrl?>" class="navbar-brand">
                        <img src="/header-logo.png" style="display: inline-block; height: 38px;"><span><?=Yii::$app->name?></span>
                    </a>

                    <button type="button" class="navbar-toggle" data-click="sidebar-toggled"><!-- sidebar-toggled --><!-- top-menu-toggled -->
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                </div>
                <!-- end mobile sidebar expand / collapse button -->

                <div class="navbar-title"><?=$navbar_icon?><span><?=$navbar_title?></span></div>
                
                <?php if(Yii::$app->user->isGuest == false): ?>
                    <?php $login = \app\models\LoginConnect::find()->where(['ip_address' => Yii::$app->request->getUserIP()])->one(); 

                    if (!$login) {
                        Yii::$app->user->logout();
                    }
                    ?>
                    <!-- begin header navigation right -->
                    <ul class="nav navbar-nav navbar-right">
                            <?php /* 
                            <li class="dropdown navbar-user">
                                <a id="btn-dropdown_header2" onclick="$(this).parent().find('ul').slideToggle('fast');" href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                    ru<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="/site/change-locale?locale=en">en</a></li>
                                    <li><a href="/site/change-locale?locale=ru">ru</a></li>
                                </ul>
                            </li>
                            */ ?>

                        <li class="dropdown navbar-user">
                            <a id="btn-dropdown_header" onclick="$(this).parent().find('ul').slideToggle('fast');" href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="/<?= Yii::$app->user->identity->getRealAvatarPath() ?>" data-role="avatar-view" alt="">
                                <span class="hidden-xs"><?= Yii::$app->user->identity->login?></span> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu animated fadeInLeft">
                                <li class="arrow"></li>
                                <li> <?= Html::a('Профиль', ['user/profile']) ?> </li>
                                <?php if(\Yii::$app->user->identity->isSuperAdmin()): ?>
                                    <li> <?= Html::a('Yandex API', ['user/yandex-token']) ?> </li>
                                <?php endif; ?>
                                <li class="divider"></li>
                                <li> <?= Html::a('Выход', ['/site/logout'], ['data-method' => 'post']) ?> </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end header navigation right -->
                <?php endif; ?>
            </div>
            <!-- end container-fluid -->
        </div>