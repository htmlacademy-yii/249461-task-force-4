<?php

use yii\widgets\ActiveForm;

?>

<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'enableAjaxValidation' => true,
                'action' => ['tasks/respond', 'id' => $task->id],
                'fieldConfig' => [
                    'labelOptions' => ['class' => 'control-label'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
                ],
            ]) ?>

            <?= $form->field($newResponse, 'comment')->textarea(); ?>
            <?= $form->field($newResponse, 'price')->textInput(); ?>

            <input type="submit" class="button button--pop-up button--blue" value="Откликнуться">

            <?php $form = ActiveForm::end() ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>