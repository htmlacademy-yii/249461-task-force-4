<?php

use yii\helpers\Html;

?>
<div id="<?= $model->id ?>" class="task-card">
    <div class="header-task">
        <a href="#" class="link link--block link--big"><?= Html::encode($model->title) ?></a>
        <p class="price price--task"><?= Html::encode($model->price) ?> ₽</p>
    </div>
    <!--<p class="info-text"><span class="current-time">4 часа </span>назад</p>-->
    <p class="info-text"><span class="current-time"><?= Html::encode($model->add_date) ?></p>
    <p class="task-text"><?= Html::encode($model->description) ?>
    </p>
    <div class="footer-task">
        <p class="info-text town-text"><?= Html::encode($model->city->name) ?></p>
        <p class="info-text category-text"><?= Html::encode($model->category->name) ?></p>
        <a href="#" class="button button--black">Смотреть Задание</a>
    </div>
</div>