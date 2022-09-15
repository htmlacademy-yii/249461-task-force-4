<?php

namespace TaskForce\classes\actions;
use TaskForce\classes\Task;

class ActionRespond extends AbstractAction
{

    protected $actionName = 'Откликнуться';
    protected $actionSystemName = 'respond';

    /**
     * @inheritDoc
     */
    function checkAvailable(Task $task, ?int $currentUser) :bool
    {
        return $currentUser !== $task->getClientId() && $task->getExecutorId() === null && $task->status === 'new';
    }
}
