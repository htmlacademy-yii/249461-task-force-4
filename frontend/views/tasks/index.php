<?php

/** @var yii\web\View $this */
/** @var \taskforce\models\Tasks $model */

use yii\helpers\Html;

$this->title = 'Новые задания';
$this->params['breadcrumbs'][] = $this->title;
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task"><?= Html::encode($this->title) ?></h3>
        <?php foreach ($tasks as $task) : ?>
        <div class="task-card">
            <div class="header-task">
                <a  href="#" class="link link--block link--big"><?=$task['title']?></a>
                <p class="price price--task"><?=$task['price']?> ₽</p>
            </div>
            <p class="info-text"><span class="current-time">4 часа </span>назад</p>
            <p class="task-text"><?=$task['description']?>
            </p>
            <div class="footer-task">
                <p class="info-text town-text"><?=$task['city']['name']?></p>
                <p class="info-text category-text"><?= $task['category']['name'] ?></p>
                <a href="#" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <form>
                    <h4 class="head-card">Категории</h4>
                    <div class="form-group">
                        <div class="checkbox-wrapper">
                            <label class="control-label" for="сourier-services">
                                <input type="checkbox" id="сourier-services" checked>
                                Курьерские услуги</label>
                            <label class="control-label" for="cargo-transportation">
                                <input id="cargo-transportation" type="checkbox">
                                Грузоперевозки</label>
                            <label class="control-label" for="translations">
                                <input id="translations" type="checkbox">
                                Переводы</label>
                        </div>
                    </div>
                    <h4 class="head-card">Дополнительно</h4>
                    <div class="form-group">
                        <label class="control-label" for="without-performer">
                            <input id="without-performer" type="checkbox" checked>
                            Без исполнителя</label>
                    </div>
                    <h4 class="head-card">Период</h4>
                    <div class="form-group">
                        <label for="period-value"></label>
                        <select id="period-value">
                            <option>1 час</option>
                            <option>12 часов</option>
                            <option>24 часа</option>
                        </select>
                    </div>
                    <input type="submit" class="button button--blue" value="Искать">
                </form>
            </div>
        </div>
    </div>
</main>