<?php

use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var $model Tasks */

$categories_list = Categories::find()->asArray()->all();
?>

<div class="add-task-form regular-form">
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'labelOptions' => ['class' => 'control-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
        ],
    ]) ?>
    <h3 class="head-main head-main">Публикация нового задания</h3>
    <?= $form->field($newTask, 'title')->error(['tag' => 'span']); ?>
    <?= $form->field($newTask, 'description')->textarea()->error(['tag' => 'span']); ?>
    <?= $form->field($newTask, 'category_id')->dropDownList(ArrayHelper::map($categories_list, 'id', 'name')) ?>
    <?= $form->field($newTask, 'address')->textInput(array('class'=>'location-icon'))->error(['tag' => 'span']); ?>
    <div class="half-wrapper">
        <?= $form->field($newTask, 'price')->textInput(array('class'=>'budget-icon'))->error(['tag' => 'span']); ?>
        <?= $form->field($newTask, 'end_date')->textInput(array('type'=>'date','class'=>'period-execution'))->error(['tag' => 'span']); ?>
    </div>
    <?= $form->field($newTask, 'taskFiles[]')->fileInput(['multiple' => true]); ?>
    <input type="submit" class="button button--blue" value="Опубликовать">
    <?php $form = ActiveForm::end() ?>
</div>