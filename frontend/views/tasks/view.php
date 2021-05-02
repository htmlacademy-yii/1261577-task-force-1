<?php

/* @var $this yii\web\View
 * @var $responsesData
 * @var $taskData
 * @var $customerData
 * @var $isExecutor bool
 * @var $availableActions
 * @var $responseUserModel
 * @var $completeTaskModel
 */

use frontend\components\widgets\ResponsesButtons;
use frontend\models\TaskStatus;
use frontend\components\widgets\StarsWidget;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use frontend\models\CompleteTaskModel;
use kartik\rating\StarRating;

$this->title = 'Detail task';
$currentUserId = Yii::$app->user->getId();

//TODO отработать вложения, адрес
//TODO я бы убрал таблицу роль из бд, добавил бы поле boolean в user
//TODO каким образом сделать фильтрацию по категориям из вьюхи
//TODO должна ли миниатрюра быть ссылкой?
//TODO отработать ситуации, если юзер удален, чтобы не ввело на его профиль
//TODO как отменить задание?
?>

<?php $this->beginBlock('responses'); ?>

    <div class="content-view__feedback">
        <h2>Отклики <span>(<?= count($responsesData); ?>)</span></h2>
        <div class="content-view__feedback-wrapper">
            <?php foreach ($responsesData as $response): ?>
                <?php $userUrl = Url::to(['users/view', 'id' => $response['user']['id']]) ?>
                <div class="content-view__feedback-card">
                    <div class="feedback-card__top">
                        <a href="<?= $userUrl; ?>"><img src="<?= $response['user']['avatar']; ?>" width="55" height="55"></a>
                        <div class="feedback-card__top--name">
                            <p><a href="<?= $userUrl; ?>" class="link-regular"><?= $response['user']['name']; ?></a></p>
                            <?= StarsWidget::widget(['rating' => $response['user']['rating']]); ?>
                            <b><?= $response['user']['rating']; ?></b>
                        </div>
                        <span class="new-task__time"><?= $response['pastTime']; ?></span>
                    </div>
                    <div class="feedback-card__content">
                        <p><?= $response['comment']; ?></p>
                        <span><?= $response['price']; ?> ₽</span>
                    </div>
                    <?php if ($currentUserId === $customerData['user']['id'] &&
                        $response['isDeleted'] == 0 &&
                        $taskData['status']['name'] === TaskStatus::NAME_STATUS_NEW): ?>
                        <?= ResponsesButtons::widget(['response' => $response, 'task' => $taskData['id']]) ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php $this->endBlock(); ?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= $taskData['short']; ?></h1>
                    <span>Размещено в категории
                        <?= Html::a($taskData['category']['name'],['tasks/'],[
                            'class' => 'link-regular',
                            'data-method' => 'GET',
                            'data-params' => [
                                'categories' => $taskData['category']['id'],
                            ]
                            ]); ?>
                                    <?= $taskData['pastTime']; ?></span>
                </div>
                <b class="new-task__price new-task__price--<?= $taskData['category']['icon']; ?> content-view-price">
                    <?= $taskData['budget']; ?><b> ₽</b>
                </b>
                <div class="new-task__icon new-task__icon--<?= $taskData['category']['icon']; ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?= $taskData['description']; ?></p>
            </div>
            <div class="content-view__attach">
                <h3 class="content-view__h3">Вложения</h3>
                <?php foreach ($taskData['images'] as $image): ?>
                <a href="<?= $image['path']; ?>" target="_blank"><?= basename($image['path']); ?></a>
                <?php endforeach; ?>
            </div>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <div id="map" style="width: 361px; height: 292px"></div>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town"><?=$taskData['address'];?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <?php if(in_array('RESPONSE_ACTION',$availableActions)): ?>
            <button class=" button button__big-color response-button open-modal"
                    type="button" data-for="response-form">Откликнуться</button>
            <?php endif; ?>
            <?php if(in_array('FAIL_ACTION',$availableActions)): ?>
            <button class="button button__big-color refusal-button open-modal"
                    type="button" data-for="refuse-form">Отказаться</button>
            <?php endif; ?>
            <?php if(in_array('COMPLETE_ACTION',$availableActions)): ?>
            <button class="button button__big-color request-button open-modal"
                    type="button" data-for="complete-form">Завершить</button>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($currentUserId === $customerData['user']['id'] || $isExecutor === true): ?>
        <?= $this->blocks['responses']; ?>
    <?php endif; ?>

</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="<?= $customerData['user']['avatar']; ?>" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= $customerData['user']['name']; ?></p>
                </div>
            </div>
            <p class="info-customer"><span><?= $customerData['countTasks']; ?> заданий</span><span class="last-"><?= $customerData['user']['registrationPast']; ?> на сайте</span></p>
            <a href="#" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div id="chat-container">
        <chat class="connect-desk__chat" task="<?= $taskData['id'];?>"></chat>
    </div>
</section>

<section class="response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <?php $form = ActiveForm::begin([
        'method' => 'POST',
        'fieldConfig' => ['template' => "<p>{label}\n{input}\n{error}</p>",
            'labelOptions' => ['class' => 'form-modal-description']],
    ]); ?>
    <?= $form->field($responseUserModel, 'price')
        ->textInput(['class' => 'response-form-payment input input-middle input-money']); ?>
    <?= $form->field($responseUserModel,'comment')
        ->textarea([
            'class' => 'input textarea',
            'rows' => '4',
            'placeholder' => 'Ваш комментарий',
        ]); ?>
    <?= Html::submitButton('Отправить', ['class' => 'button modal-button']) ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <?php $completeForm = ActiveForm::begin([
        'method' => 'POST',
        'fieldConfig' => ['template' => "<p>{label}\n{input}\n{error}</p>",
            'labelOptions' => ['class' => 'form-modal-description']],
    ]); ?>
    <?= $completeForm->field($completeTaskModel,'result')
        ->radioList(CompleteTaskModel::RESULT_STATUS,[
            'class' => 'completion-input',
            'labelOptions' => ['class' => 'completion-label']
        ]); ?>
    <?= $completeForm->field($completeTaskModel,'comment')
        ->textarea([
            'class' => 'input textarea',
            'rows' => '4',
            'placeholder' => 'Place your text',
        ]); ?>
    <?= $completeForm->field($completeTaskModel,'estimate')->widget(StarRating::class,[
        'pluginOptions' => ['step' => 1],
        'language' => 'ru',
    ]); ?>
    <?= Html::submitButton('Отправить', ['class' => 'button modal-button']) ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<section class="modal form-modal refusal-form" id="refuse-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена</button>
    <button class="button__form-modal refusal-button button fail-button" data-user="<?= \Yii::$app->user->getId(); ?>"
            data-task="<?= $taskData['id']; ?>" type="button">Отказаться</button>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<?php if(!(is_null($taskData['latitude'])&&is_null($taskData['longitude']))): ?>
    <script type="text/javascript">
        ymaps.ready(init);
        function init(){
            var myMap = new ymaps.Map("map", {
                    center: [<?=$taskData['latitude'];?>, <?=$taskData['longitude'];?>],
                    zoom: 14
                }),
                myGeoObject = new ymaps.GeoObject({
                    geometry: {
                        type: "Point",
                        coordinates: [<?=$taskData['latitude'];?>, <?=$taskData['longitude'];?>]
                    },
                    properties: {
                    }
                }, {
                    preset: 'islands#blackStretchyIcon',
                    draggable: true
                });
            myMap.geoObjects
                .add(myGeoObject);
        }
    </script>
<?php endif; ?>

<?php $this->beginBlock('yandexApi'); ?>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=<?=Yii::$app->params['apiKeyMap'];?>&lang=ru_RU"
            type="text/javascript"> </script>
<?php $this->endBlock(); ?>
