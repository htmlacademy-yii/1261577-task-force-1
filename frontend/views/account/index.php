<?php

use yii\bootstrap\ActiveForm;
//use yii\widgets\ActiveForm;
use frontend\models\UserEditModel;
use yii\bootstrap\Html;

/* @var $this yii\web\View
 *@var $userEditModel UserEditModel
 */



$this->title = 'Настройки';

$citiesList = \yii\helpers\ArrayHelper::map($cities, 'id','name');
$userCategoriesList = \yii\helpers\ArrayHelper::map($userCategories, 'id','name');
$allCategoriesList = \yii\helpers\ArrayHelper::map($allCategories, 'id','name');
?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin([
        'id' => 'account-form',
        'method' => 'POST',
        'action' => 'account',
    ]); ?>
    <div class="account__redaction-section">
        <h3 class="div-line">Настройки аккаунта</h3>
        <div class="account__redaction-section-wrapper">
            <div class="account__redaction-avatar">
                <img src="./img/man-glasses.jpg" width="156" height="156">
                <input type="file" name="avatar" id="upload-avatar">
                <label for="upload-avatar" class="link-regular">Сменить аватар</label>
            </div>
            <div class="account__redaction">
                <div class="account__input account__input--name">
                    <?= $form->field($userEditModel,'name')->input('text',[
                        'class' => 'input textarea',
                        'placeholder' => $user['name'],
                        'id' => "200",
                    ]); ?>
                </div>
                <div class="account__input account__input--email">
                    <?= $form->field($userEditModel,'email')->input('text',[
                        'class' => 'input textarea',
                        'placeholder' => $user['email'],
                        'id' => "201",
                    ]); ?>
                </div>
                <div class="account__input account__input--name">
                    <?= $form->field($userEditModel,'city')->dropDownList($citiesList,[
                        'class' => 'multiple-select input multiple-select-big',
                        'id' => '202',
                        'options' => [$user['city_id'] => ['Selected' => true]],
                    ]); ?>
                </div>
                <div class="account__input account__input--date">
                    <?= $form->field($userEditModel,'birthday')->input('date',[
                        'class' => 'input-middle input input-date',
                        'id' => "203",
                    ]); ?>
                </div>
                <div class="account__input account__input--info">
                    <?= $form->field($userEditModel,'about')->input('text',[
                        'class' => 'input textarea',
                        'placeholder' => $user['detail']['about'],
                        'id' => "204",
                        'rows' => '7',
                    ]); ?>
                </div>
            </div>
        </div>
        <h3 class="div-line">Выберите свои специализации</h3>
        <div class="account__redaction-section-wrapper">
            <div class="search-task__categories account_checkbox--bottom">
                    <?= $form->field($userEditModel,'categories[]')->checkboxList($allCategoriesList,[
                        'item' => function($index,$label,$name,$checked,$value) {
                        return '<div class="col-md-4">'.Html::checkbox($name,$checked,['label'=>$label,'value'=>$value]).'</div>';
                        }
                    ])->label(false); ?>
            </div>
        </div>
    </div>


    <?= Html::submitButton('Сохранить') ?>

    <?php ActiveForm::end(); ?>

<!--    <form>-->
<!--        <div class="account__redaction-section">-->
<!--            <h3 class="div-line">Настройки аккаунта</h3>-->
<!--            <div class="account__redaction-section-wrapper">-->
<!--                <div class="account__redaction-avatar">-->
<!--                    <img src="./img/man-glasses.jpg" width="156" height="156">-->
<!--                    <input type="file" name="avatar" id="upload-avatar">-->
<!--                    <label for="upload-avatar" class="link-regular">Сменить аватар</label>-->
<!--                </div>-->
<!--                <div class="account__redaction">-->
<!--                    <div class="account__input account__input--name">-->
<!--                        <label  for="200">Ваше имя</label>-->
<!--                        <input class="input textarea"  id="200" name="" placeholder="--><?//= $user['name']; ?><!--">-->
<!--                    </div>-->
<!--                    <div class="account__input account__input--email" >-->
<!--                        <label for="201">email</label>-->
<!--                        <input class="input textarea" id="201" name="" placeholder="--><?//= $user['email']; ?><!--">-->
<!--                    </div>-->
<!--                    <div class="account__input account__input--name">-->
<!--                        <label  for="202">Город</label>-->
<!--                        <select class="multiple-select input multiple-select-big" size="1" id="202" name="town[]">-->
<!--                            --><?php //foreach ($cities as $city):?>
<!--                            --><?php //if($city['id'] == $user['city_id']): ?>
<!--                                <option value="--><?//= $city['id']; ?><!--" selected>--><?//= $city['name'];?><!--</option>-->
<!--                                    --><?php //else:?>
<!--                                    <option value="--><?//= $city['id']; ?><!--">--><?//= $city['name'];?><!--</option>-->
<!--                            --><?php //endif;?>
<!--                            --><?php //endforeach; ?>
<!--                        </select>-->
<!--                    </div>-->
<!--                    <div class="account__input account__input--date">-->
<!--                        <label for="203">День рождения</label>-->
<!--                        <input id="203"  class="input-middle input input-date" type="date" placeholder="15.08.1987">-->
<!--                    </div>-->
<!--                    <div class="account__input account__input--info">-->
<!--                        <label for="204">Информация о себе</label>-->
<!--                        <textarea class="input textarea" rows="7" id="204" name="" placeholder="Place your text"></textarea>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <h3 class="div-line">Выберите свои специализации</h3>-->
<!--            <div class="account__redaction-section-wrapper">-->
<!--                <div class="search-task__categories account_checkbox--bottom">-->
<!--                    <input class="visually-hidden checkbox__input" id="205" type="checkbox" name="" value="" checked>-->
<!--                    <label for="205">Курьерские услуги</label>-->
<!--                    <input class="visually-hidden checkbox__input" id="206" type="checkbox" name="" value="" checked>-->
<!--                    <label  for="206">Грузоперевозки</label>-->
<!--                    <input class="visually-hidden checkbox__input" id="207" type="checkbox" name="" value="">-->
<!--                    <label for="207">Перевод текстов</label>-->
<!--                    <input class="visually-hidden checkbox__input" id="208" type="checkbox" name="" value="" checked>-->
<!--                    <label for="208">Ремонт транспорта</label>-->
<!--                    <input class="visually-hidden checkbox__input" id="209" type="checkbox" name="" value="">-->
<!--                    <label  for="209">Удалённая помощь</label>-->
<!--                    <input class="visually-hidden checkbox__input" id="210" type="checkbox" name="" value="">-->
<!--                    <label  for="210">Выезд на стрелку</label>-->
<!--                </div>-->
<!--            </div>-->
<!--            <h3 class="div-line">Безопасность</h3>-->
<!--            <div class="account__redaction-section-wrapper account__redaction">-->
<!--                <div class="account__input">-->
<!--                    <label  for="211">Новый пароль</label>-->
<!--                    <input class="input textarea"  type="password" id="211" name="" value="moiparol">-->
<!--                </div>-->
<!--                <div class="account__input">-->
<!--                    <label for="212">Повтор пароля</label>-->
<!--                    <input class="input textarea" type="password" id="212" name="" value="moiparol">-->
<!--                </div>-->
<!--            </div>-->
<!--            <h3 class="div-line">Контакты</h3>-->
<!--            <div class="account__redaction-section-wrapper account__redaction">-->
<!--                <div class="account__input">-->
<!--                    <label  for="213">Телефон</label>-->
<!--                    <input class="input textarea"  type="tel" id="213" name="" placeholder="8 (555) 187 44 87">-->
<!--                </div>-->
<!--                <div class="account__input">-->
<!--                    <label for="214">Skype</label>-->
<!--                    <input class="input textarea" type="password" id="214" name="" placeholder="DenisT">-->
<!--                </div>-->
<!--                <div class="account__input" >-->
<!--                    <label for="215">Telegram</label>-->
<!--                    <input class="input textarea" id="215" name="" placeholder="@DenisT">-->
<!--                </div>-->
<!--            </div>-->
<!--            <h3 class="div-line">Настройки сайта</h3>-->
<!--            <h4>Уведомления</h4>-->
<!--            <div class="account__redaction-section-wrapper account_section--bottom">-->
<!--                <div class="search-task__categories account_checkbox--bottom">-->
<!--                    <input class="visually-hidden checkbox__input" id="216" type="checkbox" name="" value="" checked>-->
<!--                    <label for="216">Новое сообщение</label>-->
<!--                    <input class="visually-hidden checkbox__input" id="217" type="checkbox" name="" value="" checked>-->
<!--                    <label  for="217">Действия по заданию</label>-->
<!--                    <input class="visually-hidden checkbox__input" id="218" type="checkbox" name="" value="" checked>-->
<!--                    <label for="218">Новый отзыв</label>-->
<!--                </div>-->
<!--                <div class="search-task__categories account_checkbox account_checkbox--secrecy">-->
<!--                    <input class="visually-hidden checkbox__input" id="219" type="checkbox" name="" value="">-->
<!--                    <label for="219">Показывать мои контакты только заказчику</label>-->
<!--                    <input class="visually-hidden checkbox__input" id="220" type="checkbox" name="" value="" checked>-->
<!--                    <label  for="220">Не показывать мой профиль</label>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <button class="button" type="submit">Сохранить изменения</button>-->
<!--    </form>-->
</section>
