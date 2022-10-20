<?php

/** @var yii\web\View $this */
/** @var \app\models\forms\UserRegistration $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Cities;
use yii\helpers\ArrayHelper;

$cities_list = Cities::find()->asArray()->all();

$this->title = 'Регистрация нового пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
            'fieldConfig' => [
                'inputOptions' => ['class' => null],
                'labelOptions' => ['class' => 'control-label'],
                'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
            ],
        ]); ?>
        <h3 class="head-main head-task">Регистрация нового пользователя</h3>
        <div class="form-group">
            <?= $form->field($newUser, 'name') ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($newUser, 'email') ?>

            <?= $form->field($newUser, 'city_id')->dropDownList(ArrayHelper::map($cities_list, 'id', 'name')) ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($newUser, 'password')->passwordInput() ?>
        </div>
        <div class="half-wrapper">

            <?= $form->field($newUser, 'password_retype')->passwordInput() ?>
        </div>
        <?= $form->field($newUser, 'is_worker')->checkbox(); ?>
        <input type="submit" class="button button--blue" value="Создать аккаунт">
        <?php $form = ActiveForm::end(); ?>
    </div>
</div>