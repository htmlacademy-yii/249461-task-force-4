<?php

namespace app\services\task;

use app\models\Responses;
use app\models\Tasks;

class TaskActionRespond extends TaskAbstractAction
{

    protected $actionName = 'Откликнуться';
    protected $actionSystemName = 'respond';

    /**
     * @inheritDoc
     */
    function checkAvailable($task, ?int $currentUser) :bool
    {
        return $currentUser !== $task->author_id && $task->worker_id === null && $task->status === Tasks::STATUS_NEW;
    }

    public function execute($task_id, $newResponse, $user_id) {
        $response = new Responses();
        $response->task_id = $task_id;
        $response->comment = $newResponse->comment;
        $response->price = $newResponse->price;
        $response->user_id = $user_id;

        $response->save();
    }
}
