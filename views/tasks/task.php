<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\services\DateServices;
use app\services\TaskCreateService;

$dateServices = new DateServices();
$taskCreateService = new TaskCreateService();


$this->title = $task->title;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
        <p class="price price--big"><?= $task->price ? Html::encode($task->price) . ' ₽' : 'Договорная' ?></p>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>
    <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
    <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
    <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>

    <?php if (!empty($task->address)) : ?>
    <div class="task-map">
        <img class="map" src="../img/map.png" width="725" height="346" alt="<?= Html::encode($task->address) ?>">
        <p class="map-address town"><?= Html::encode($task->city->name) ?></p>
        <p class="map-address"><?= Html::encode($task->address) ?></p>
    </div>
    <?php endif; ?>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php if (!!$task->responses) : ?>
        <?php foreach ($task->responses as $responce) : ?>
            <div class="response-card">
                <img class="customer-photo" src="/<?= $responce->user->avatar ?>" width="146" height="156"
                     alt="Фото заказчиков">
                <div class="feedback-wrapper">
                    <a href="<?=Url::toRoute(['users/view/','id' => $responce->user->id]); ?>" class="link link--block link--big"><?= Html::encode($responce->user->name) ?></a>
                    <div class="response-wrapper">
                        <div class="stars-rating small">
                            <span class="fill-star">&nbsp;</span>
                            <span class="fill-star">&nbsp;</span>
                            <span class="fill-star">&nbsp;</span>
                            <span class="fill-star">&nbsp;</span>
                            <span>&nbsp;</span>
                        </div>
                        <p class="reviews"><?= count($responce->user->workerReviews) ?> отзыва</p>
                    </div>
                    <p class="response-message">
                        <?= Html::encode($responce->comment) ?>
                    </p>
                </div>
                <div class="feedback-wrapper">
                    <p class="info-text"><span class="current-time"><?= $dateServices->elapsed_time($responce->add_date) ?></p>
                    <p class="price price--small"><?= Html::encode($responce->price) ?> ₽</p>
                </div>
                <div class="button-popup">
                    <a href="#" class="button button--blue button--small">Принять</a>
                    <a href="#" class="button button--orange button--small">Отказать</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Откликов пока нет, будь первым!</p>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd><?= Html::encode($task->category->name) ?></dd>
            <dt>Дата публикации</dt>
            <dd><?= $dateServices->elapsed_time($task->add_date) ?></dd>
            <?php if ($task->end_date): ?>
                <dt>Срок выполнения</dt>
                <dd><?= Yii::$app->formatter->asDatetime($task->end_date) ?></dd>
            <?php endif; ?>
            <dt>Статус</dt>
            <dd><?= Html::encode($task->getStatusName()) ?></dd>
        </dl>
    </div>
    <?php if ($task->taskFiles) : ?>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>

            <ul class="enumeration-list">
                <?php foreach ($task->taskFiles as $file) : ?>
                <li class="enumeration-item">
                    <a href="<?=$file->path;?>" download="<?=$file->name;?>" class="link link--block link--clip"><?=$file->name;?></a>
                    <p class="file-size"><?=$taskCreateService->fileSize($file->path)?></p>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <form>
                <div class="form-group">
                    <label class="control-label" for="completion-comment">Ваш комментарий</label>
                    <textarea id="completion-comment"></textarea>
                </div>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars">
                    <span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
                </div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <form>
                <div class="form-group">
                    <label class="control-label" for="addition-comment">Ваш комментарий</label>
                    <textarea id="addition-comment"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label" for="addition-price">Стоимость</label>
                    <input id="addition-price" type="text">
                </div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<div class="overlay"></div>