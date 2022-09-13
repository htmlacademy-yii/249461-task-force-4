<?php

namespace TaskForce\classes\actions;
use TaskForce\classes\Task;

class ActionStart extends AbstractAction
{

    protected $actionName = 'Принять';
    protected $actionSystemName = 'start';

    /**
     * @inheritDoc
     */
    function checkAvailable(Task $task, ?int $currentUser) :bool
    {
        return $currentUser === $task->getClientId() && $task->status === 'new';
    }
}
