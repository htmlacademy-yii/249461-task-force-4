<?php

namespace TaskForce\classes\actions;
use TaskForce\classes\Task;

class ActionRefuse extends AbstractAction
{

    protected $actionName = 'Отказаться';
    protected $actionSystemName = 'refuse';

    /**
     * @inheritDoc
     */
    function checkAvailable(Task $task, ?int $currentUser) :bool
    {
        return $currentUser === $task->getExecutorId() && $task->status === 'progress';
    }
}
