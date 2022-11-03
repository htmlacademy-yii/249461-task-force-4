<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\services\DateServices;

$dateServices = new DateServices();

?>
<div id="<?= $model->id ?>" class="task-card">
    <div class="header-task">
        <a href="<?= Url::toRoute(['tasks/view/', 'id' => $model->id]); ?>"
           class="link link--block link--big"><?= Html::encode($model->title) ?></a>
        <p class="price price--task"><?= $model->price ? Html::encode($model->price) . ' ₽' : 'Договорная' ?></p>
    </div>
    <p class="info-text"><span class="current-time"><?= $dateServices->elapsed_time($model->add_date) ?></p>
    <p class="task-text"><?= Html::encode($model->description) ?>
    </p>
    <div class="footer-task">
        <?php if (!empty($model->city->name)) : ?>
            <p class="info-text town-text"><?= Html::encode($model->city->name) ?></p>
        <?php endif; ?>
        <p class="info-text category-text"><?= Html::encode($model->category->name) ?></p>
        <a href="<?= Url::toRoute(['tasks/view/', 'id' => $model->id]); ?>" class="button button--black">Смотреть Задание</a>
    </div>
</div>