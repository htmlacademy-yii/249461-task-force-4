<?php

namespace TaskForce\classes\actions;

class ActionComplete extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function getActionName()
    {
        return 'Выполнено';
    }

    /**
     * @inheritDoc
     */
    function getActionSystemName()
    {
        return 'complete';
    }

    /**
     * @inheritDoc
     */
    function userRoleCheck($currentUser, $clientId, $executorId)
    {
        return $currentUser === $clientId;
    }
}
