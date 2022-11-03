<?php

/** @var yii\web\View $this */
/** @var \app\models\Tasks $model */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Новые задания';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="left-column">
    <h3 class="head-main head-task"><?= Html::encode($this->title) ?></h3>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_taskslist_item',

        'summary' => '',

        'pager' => [
            'maxButtonCount' => 5,
            'options' => [
                'class' => 'pagination-list',
            ],
            'linkOptions' => [
                'class' => 'link link--page',
            ],
            'pageCssClass' => 'pagination-item',
            'activePageCssClass' => 'pagination-item--active',
            'prevPageCssClass' => 'pagination-item mark',
            'nextPageCssClass' => 'pagination-item mark',
            'prevPageLabel' => '',
            'nextPageLabel' => '',
        ],
    ]);
    ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <?= $this->render('_taskslist_filter', ['tasksFilterForm' => $tasksFilterForm]) ?>
    </div>
</div>
