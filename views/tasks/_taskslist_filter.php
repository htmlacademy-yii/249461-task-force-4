<?php

use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var $model Tasks */

$categories_list = Categories::find()->asArray()->all();

?>

<div class="search-form">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ])?>
    <?/*= $form->field($model, 'category_id', [
        'template' => "{label}\n{input}",
    ]) */?>

    <?php /*var_dump($form->field($model, 'category_id', ['options' => ['class' => 'filters-block__field field']])->checkbox()); */?>
</div>
