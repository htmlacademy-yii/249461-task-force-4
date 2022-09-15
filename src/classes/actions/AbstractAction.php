<?php

namespace TaskForce\classes\actions;
use TaskForce\classes\Task;

/**
 *
 */
abstract class AbstractAction
{
    protected $actionName;
    protected $actionSystemName;

    /**
     * Возвращает название
     */
    function getActionName() {
        return $this->actionName;
    }

    /**
     * Возвращает внутреннее название
     */
    function getActionSystemName() {
        return $this->actionSystemName;
    }


    /**
     *
     * @param Task $task
     * @param int|null $currentUser
     * @return mixed
     */
    abstract function checkAvailable(Task $task, ?int $currentUser) :bool;
}
