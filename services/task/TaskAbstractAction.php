<?php

namespace app\services\task;

use app\models\Tasks;

/**
 *
 */
abstract class TaskAbstractAction
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
    abstract function checkAvailable($task, ?int $currentUser) :bool;
}
