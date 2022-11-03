<?php

namespace app\services\task;

use app\models\Tasks;

class TaskActionCancel extends TaskAbstractAction
{

    protected $actionName = 'Отменить';
    protected $actionSystemName = 'cancel';

    /**
     * @inheritDoc
     */
    function checkAvailable($task, ?int $currentUser) :bool
    {
        return $currentUser === $task->author_id && $task->status === Tasks::STATUS_NEW;
    }

    public function execute($task) {
        $task->status = Tasks::STATUS_CANCELED;
        $task->update();
    }
}
