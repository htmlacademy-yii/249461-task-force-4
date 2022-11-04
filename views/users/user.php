<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\services\DateServices;
use app\services\UserServices;

$dateServices = new DateServices();
$userServices = new UserServices();

$this->title = $user->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->name) ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="/<?= $user->avatar ?>" width="191" height="190" alt="Фото пользователя">
            <div class="card-rate">
                <?php $userServices->renderStarRating($user->rating, 'big'); ?>
                <span class="current-rate"><?= Html::encode($user->rating) ?></span>
            </div>
        </div>
        <p class="user-description">
            <?= Html::encode($user->about_me) ?>
        </p>
    </div>
    <div class="specialization-bio">
        <?php if (!empty($user->userCategories)) : ?>
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach ($user->userCategories as $userCategory) : ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?= Html::encode($userCategory->category->name) ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info"><span class="country-info">Россия</span>, <span class="town-info"><?= Html::encode($user->city->name) ?></span>, <span class="age-info"><?= $dateServices->countUserAge($user->birthday) ?></span></p>
        </div>
    </div>
    <?php if (!empty($user->workerReviews)) : ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($user->workerReviews as $userReview) : ?>
        <div class="response-card">
            <img class="customer-photo" src="/<?= $userReview->author->avatar ?>" width="120" height="127" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <p class="feedback"><?= Html::encode($userReview->review) ?></p>
                <p class="task">Задание «<a href="<?=Url::toRoute(['tasks/view/','id' => $userReview->task->id]); ?>" class="link link--small"><?= Html::encode($userReview->task->title) ?></a>» выполнено</p>
            </div>
            <div class="feedback-wrapper">
                <?php $userServices->renderStarRating($userReview->mark, ); ?>
                <p class="info-text"><span class="current-time"><?= $dateServices->elapsed_time($userReview->add_date) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd><?= Html::encode($user->tasks_completed) ?> выполнено, <?= Html::encode($user->tasks_failed) ?> провалено</dd>
            <dt>Место в рейтинге</dt>
            <dd>25 место</dd>
            <dt>Дата регистрации</dt>
            <dd><?= $dateServices->elapsed_time($user->reg_date) ?></dd>
            <?php if ($user->is_worker === 1) : ?>
            <dt>Статус</dt>
            <dd>Открыт для новых заказов</dd>
            <?php endif; ?>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <?php if ($user->phone) : ?>
            <li class="enumeration-item">
                <a href="tel:<?= Html::encode($user->phone) ?>" class="link link--block link--phone"><?= Html::encode($user->phone) ?></a>
            </li>
            <?php endif;?>
            <?php if ($user->email) : ?>
            <li class="enumeration-item">
                <a href="mailto:<?= Html::encode($user->email) ?>" class="link link--block link--email"><?= Html::encode($user->email) ?></a>
            </li>
            <?php endif;?>
            <?php if ($user->telegram) : ?>
            <li class="enumeration-item">
                <a href="t.me/<?= Html::encode($user->telegram) ?>" class="link link--block link--tg"><?= Html::encode($user->telegram) ?></a>
            </li>
            <?php endif;?>
        </ul>
    </div>
</div>
