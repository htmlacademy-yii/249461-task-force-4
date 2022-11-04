<?php

namespace app\services;

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
}