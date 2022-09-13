<?php

namespace TaskForce\classes\actions;
use TaskForce\classes\Task;

class ActionCancel extends AbstractAction
{

    protected $actionName = 'Отменить';
    protected $actionSystemName = 'cancel';

    /**
     * @inheritDoc
     */
    function checkAvailable(Task $task, ?int $currentUser) :bool
    {
        return $currentUser === $task->getClientId() && $task->status === 'new';
    }
}
