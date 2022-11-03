<?php

use yii\widgets\ActiveForm;

?>

<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'enableAjaxValidation' => true,
                'id' => $newReview->formName(),
                'action' => ['tasks/review', 'id' => $task->id],
                'fieldConfig' => [
                    'labelOptions' => ['class' => 'control-label'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
                ],
            ]) ?>

            <?= $form->field($newReview, 'review')->textarea(); ?>
            <p class="completion-head control-label">Оценка работы</p>
            <div class="stars-rating big active-stars">
                <span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
            </div>
            <?= $form->field($newReview, 'mark',['template' => '{input}{error}',])->hiddenInput(); ?>

            <input type="submit" class="button button--pop-up button--blue" value="Завершить">

            <?php $form = ActiveForm::end() ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>