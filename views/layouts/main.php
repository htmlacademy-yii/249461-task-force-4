<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use app\assets\MainAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;

MainAsset::register($this);

$currentPage = Yii::$app->request->pathInfo;

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <header class="page-header">
        <nav class="main-nav">
            <a href='/' class="header-logo">
                <img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
            </a>
            <?php if ($currentPage !== 'registration') : ?>
            <div class="nav-wrapper">
                <ul class="nav-list">
                    <li class="list-item list-item--active">
                        <a href="/tasks" class="link link--nav" >Новое</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav">Мои задания</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav">Создать задание</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav">Настройки</a>
                    </li>
                </ul>
            </div>
            <?php endif;?>
        </nav>
        <?php if ($currentPage !== 'registration') : ?>
        <div class="user-block">
            <a href="#">
                <img class="user-photo" src="/img/man-glasses.png" width="55" height="55" alt="Аватар">
            </a>
            <div class="user-menu">
                <p class="user-name"><?=(Yii::$app->user->identity->name);?></p>
                <div class="popup-head">
                    <ul class="popup-menu">
                        <li class="menu-item">
                            <a href="#" class="link">Настройки</a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="link">Связаться с нами</a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= Url::to('/landing/logout') ;?>" class="link">Выход из системы</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif;?>
    </header>
    <main class="main-content container">
    <?= $content ?>
    </main>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
