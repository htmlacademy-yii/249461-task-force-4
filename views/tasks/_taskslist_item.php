<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div id="<?= $model->id ?>" class="task-card">
    <div class="header-task">
        <a href="<?=Url::toRoute(['tasks/view/','id' => $model->id]); ?>" class="link link--block link--big"><?= Html::encode($model->title) ?></a>
        <p class="price price--task"><?= Html::encode($model->price) ?> ₽</p>
    </div>
    <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->format($model->add_date, 'relativeTime') ?></p>
    <p class="task-text"><?= Html::encode($model->description) ?>
    </p>
    <div class="footer-task">
        <p class="info-text town-text"><?= Html::encode($model->city->name) ?></p>
        <p class="info-text category-text"><?= Html::encode($model->category->name) ?></p>
        <a href="#" class="button button--black">Смотреть Задание</a>
    </div>
</div>