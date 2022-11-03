<?php

namespace app\services;

use Yii;
use app\models\Reviews;
use app\models\Tasks;
use app\models\Users;
use app\models\Responses;

class TaskViewServices
{
    /**
     * Проверям что пользователя является автором задачи
    */
    public function checkTaskAuthor($task, $current_user)
    {
        return $task->author_id === $current_user->id;
    }

    /**
     * Проверям что пользователя является исполниьтелем задачи
     */
    public function checkTaskWorker($task, $current_user)
    {
        return $task->worker_id === $current_user->id;
    }

    /**
     * Проверям есть ли уже отклик для задачи у текущего пользователя
     */
    public function checkUserResponse($task_id, $user_id)
    {
        $response = new Responses();
        $resp = $response::find()->where(['task_id' => $task_id, 'user_id' => $user_id])->one();

        return empty($resp);
    }
    
    /**
     * Формируем и добавляем отклик в таблицу откликов
     */
    public function addResponse($task_id, $newResponse) {
        $response = new Responses();
        $response->task_id = $task_id;
        $response->comment = $newResponse->comment;
        $response->price = $newResponse->price;
        $response->user_id = Yii::$app->user->identity->id;

        $response->save();
    }

    /**
     * Формируем и добавляем отзыв в таблицу откликов
     */
    public function addReview($taskId, $newReview) {
        $task = Tasks::findOne($taskId);

        /*Новый отзыв*/
        $review = new Reviews();
        $review->task_id = $task->id;
        $review->review = $newReview->review;
        $review->mark = $newReview->mark;
        $review->author_id = $task->author_id;
        $review->worker_id = $task->worker_id;

        /*У таска новый статус*/
        $task->status = Tasks::STATUS_COMPLETED;

        /*У исполнителя + 1 выполненый таск*/
        $worker = Users::findOne($task->worker_id);
        $worker->tasks_completed = $worker->tasks_completed + 1;

        $transaction = Yii::$app->db->beginTransaction();

        if ($review->save() && $task->update() && $worker->update()) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }
    }
}