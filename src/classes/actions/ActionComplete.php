<?php

namespace TaskForce\classes\actions;
use TaskForce\classes\Task;

class ActionComplete extends AbstractAction
{

    protected $actionName = 'Выполнено';
    protected $actionSystemName = 'complete';

    /**
     * @inheritDoc
     */
    function checkAvailable(Task $task, ?int $currentUser) :bool
    {
        return $currentUser === $task->getClientId() && $task->status === 'progress';
    }
}
