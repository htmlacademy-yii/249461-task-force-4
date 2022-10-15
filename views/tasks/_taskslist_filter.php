<?php

use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var $model Tasks */

$categories_list = Categories::find()->asArray()->all();

$checkboxTemplateCallback = function ($index, $label, $name, $checked, $value) {
    $checked = $checked ? 'checked' : '';
    return '<label class="control-label">'
        . Html::checkbox($name, $checked, ['value' => $value, 'id' => $index])
        . $label . '</label>';
};

?>

<div class="search-form">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'fieldConfig' => [
            'template' => "{input}",
            'options' => ['tag' => false],
        ]
    ]) ?>

    <h4 class="head-card">Категории</h4>
    <?= $form->field($tasksFilterForm, 'categories')
        ->checkboxList(ArrayHelper::map($categories_list, 'id', 'name'),
            [
                'class' => 'checkbox-wrapper',
                'item' => $checkboxTemplateCallback
            ]); ?>

    <h4 class="head-card">Дополнительно</h4>
    <div class="form-group">
    <?= $form->field($tasksFilterForm, 'remoteWork')
        ->checkbox([
            'id' => 'remoteWork',
            'labelOptions' => [
                'class' => 'control-label',
            ]
        ]); ?>
    </div>

    <div class="form-group">
    <?= $form->field($tasksFilterForm, 'withoutResponses')
        ->checkbox([
            'id' => 'withoutResponses',
            'labelOptions' => [
                'class' => 'control-label',
            ]
        ]); ?>
    </div>

    <h4 class="head-card">Период</h4>
    <?= $form->field($tasksFilterForm, 'period', [
            'template' => "{label}\n{input}",
            'labelOptions' => [
            'for' => 'period-value'],
            'inputOptions' => ['id' => 'period-value']

        ])
        ->dropDownList($tasksFilterForm::getPeriodValue(), [])->label(false);
    ?>

    <input type="submit" class="button button--blue" value="Искать">

    <?php $form = ActiveForm::end() ?>
</div>
