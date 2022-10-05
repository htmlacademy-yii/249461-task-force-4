<div id="<?= $model->id ?>" class="task-card">
    <div class="header-task">
        <a href="#" class="link link--block link--big"><?= $model->title ?></a>
        <p class="price price--task"><?= $model->price ?> ₽</p>
    </div>
    <p class="info-text"><span class="current-time">4 часа </span>назад</p>
    <p class="task-text"><?= $model->description ?>
    </p>
    <div class="footer-task">
        <p class="info-text town-text"><?= $model->city->name ?></p>
        <p class="info-text category-text"><?= $model->category->name ?></p>
        <a href="#" class="button button--black">Смотреть Задание</a>
    </div>
</div>