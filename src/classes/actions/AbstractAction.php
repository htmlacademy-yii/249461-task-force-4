<?php

namespace TaskForce\classes\actions;

abstract class AbstractAction
{

    /**
     * Возвращает название
     */
    abstract function getActionName();

    /**
     * Возвращает внутреннее название
     */
    abstract function getActionSystemName();

    /**
     * Проверяет права пользователя для текущего действия
     */
    abstract function userRoleCheck($currentUser, $clientId, $executorId);
}
