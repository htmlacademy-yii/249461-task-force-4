<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\services\DateServices;
use app\services\TaskCreateService;
use app\models\Tasks;
use app\models\Users;
use app\services\TaskViewServices;

$dateServices = new DateServices();
$taskCreateService = new TaskCreateService();
$taskViewServices = new TaskViewServices;
$current_user = Yii::$app->user->identity;

$this->title = $task->title;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
        <p class="price price--big"><?= $task->price ? Html::encode($task->price) . ' ₽' : 'Договорная' ?></p>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>
    <?php if ($task->status == Tasks::STATUS_NEW) : ?>
        <?php if ((Users::checkIsWorker($current_user) && $task->worker_id == null) && $taskViewServices->checkUserResponse($task->id, $current_user->id)) : ?>
            <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
        <?php elseif(Users::checkIsClient($current_user) && $taskViewServices->checkTaskAuthor($task, $current_user)): ?>
            <a href="<?= Url::to(['tasks/cancel/', 'id' => $task->id]); ?>" class="button button--yellow action-btn" data-action="act_cancel">Отменить задание</a>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($task->status == Tasks::STATUS_PROGRESS) : ?>
        <?php if (Users::checkIsWorker($current_user) && $taskViewServices->checkTaskWorker($task, $current_user)) : ?>
            <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
        <?php elseif(Users::checkIsClient($current_user) && $taskViewServices->checkTaskAuthor($task, $current_user)): ?>
            <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($task->address)) : ?>
    <div class="task-map">
        <img class="map" src="/img/map.png" width="725" height="346" alt="<?= Html::encode($task->address) ?>">
        <?php if (!empty($task->city->name)) : ?>
            <p class="map-address town"><?= Html::encode($task->city->name) ?></p>
        <?php endif; ?>
        <p class="map-address"><?= Html::encode($task->address) ?></p>
    </div>
    <?php endif; ?>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php if (!!$task->responses) : ?>
        <?php foreach ($task->responses as $responce) : ?>
            <div class="response-card <?= $responce->rejected===1 ? 'response-card--rejected' : ''?>">
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
                <?php if ($task->status == Tasks::STATUS_NEW && $taskViewServices->checkTaskAuthor($task, $current_user) && $responce->rejected!==1):?>
                <div class="button-popup">
                    <a href="<?= Url::to(['tasks/start/', 'id' => $task->id, 'worker_id' => $responce->user_id]); ?>" class="button button--blue button--small">Принять</a>
                    <a href="<?= Url::to(['tasks/reject/', 'id' => $responce->id]); ?>" class="button button--orange button--small">Отказать</a>
                </div>
                <?php endif; ?>
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
                    <p class="file-size"><?=$taskCreateService->showFileSize($file->path)?></p>
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
        <a href="<?= Url::to(['tasks/refuse/', 'id' => $task->id, 'worker_id' => $task->worker_id]); ?>" class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<!--<section class="pop-up pop-up--completion pop-up--close">
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
</section>-->
<?= $this->render('_add_review_form', ['newReview' => $newReview]) ?>
<?= $this->render('_add_response_form', ['newResponse' => $newResponse]) ?>
<div class="overlay"></div>