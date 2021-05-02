<?php

use yii\helpers\Url;

$logoutUrl = Url::to(['logout/']);
$currentUser = Yii::$app->user->identity->name;
$myList = Url::to(['my-list/new']);
?>
<div class="header__town">
    <select class="multiple-select input town-select" size="1" name="town[]">
        <option value="Moscow">Москва</option>
        <option selected value="SPB">Санкт-Петербург</option>
        <option value="Krasnodar">Краснодар</option>
        <option value="Irkutsk">Иркутск</option>
        <option value="Vladivostok">Владивосток</option>
    </select>
</div>
<div class="header__lightbulb"></div>
<div class="lightbulb__pop-up">
    <h3>Новые события</h3>
    <p class="lightbulb__new-task lightbulb__new-task--message">
        Новое сообщение в чате
        <a href="#" class="link-regular">«Помочь с курсовой»</a>
    </p>
    <p class="lightbulb__new-task lightbulb__new-task--executor">
        Выбран исполнитель для
        <a href="#" class="link-regular">«Помочь с курсовой»</a>
    </p>
    <p class="lightbulb__new-task lightbulb__new-task--close">
        Завершено задание
        <a href="#" class="link-regular">«Помочь с курсовой»</a>
    </p>
</div>
<div class="header__account">
    <a class="header__account-photo">
        <img src="/img/user-photo.png"
             width="43" height="44"
             alt="Аватар пользователя">
    </a>
    <span class="header__account-name">
                     <?= $currentUser; ?>
                 </span>
</div>
<div class="account__pop-up">
    <ul class="account__pop-up-list">
        <li>
            <a href="<?= $myList; ?>">Мои задания</a>
        </li>
        <li>
            <a href="#">Настройки</a>
        </li>
        <li>
            <a href="<?= $logoutUrl; ?>">Выход</a>
        </li>
    </ul>
</div>
