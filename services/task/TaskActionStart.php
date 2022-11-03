<?php

namespace app\services\task;

use app\models\Tasks;

class TaskActionStart extends TaskAbstractAction
{

    protected $actionName = 'Принять';
    protected $actionSystemName = 'start';

    /**
     * @inheritDoc
     */
    function checkAvailable($task, ?int $currentUser) :bool
    {
        return $currentUser === $task->author_id && $task->status === Tasks::STATUS_NEW;
    }

    public function execute($task, $worker_id) {
        $task->status = Tasks::STATUS_PROGRESS;
        $task->worker_id = $worker_id;
        $task->update();
    }
}
