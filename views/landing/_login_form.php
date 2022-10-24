<?php

use yii\widgets\ActiveForm;

?>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>

    <?php
    $form = ActiveForm::begin([
        'id' => $loginForm->formName(),
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'inputOptions' => ['class' => 'enter-form-email input input-middle'],
            'labelOptions' => ['class' => 'form-modal-description'],
        ],
    ]);
    ?>

    <?= $form->field($loginForm, 'email')->error(['tag' => 'div']); ?>

    <?= $form->field($loginForm, 'password')->passwordInput()->error(['tag' => 'div'] ); ?>

    <button class="button" type="submit">Войти</button>

    <?php ActiveForm::end() ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>