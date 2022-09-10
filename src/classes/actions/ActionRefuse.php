<?php

namespace TaskForce\classes\actions;

class ActionRefuse extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function getActionName()
    {
        return 'Отказаться';
    }

    /**
     * @inheritDoc
     */
    function getActionSystemName()
    {
        return 'refuse';
    }

    /**
     * @inheritDoc
     */
    function userRoleCheck($currentUser, $clientId, $executorId)
    {
        return $currentUser === $executorId;
    }
}
