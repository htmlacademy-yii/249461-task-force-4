<?php

namespace TaskForce\classes\actions;

class ActionStart extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function getActionName()
    {
        return 'Принять';
    }

    /**
     * @inheritDoc
     */
    function getActionSystemName()
    {
        return 'start';
    }

    /**
     * @inheritDoc
     */
    function userRoleCheck($currentUser, $clientId, $executorId)
    {
        return $currentUser === $clientId;
    }
}
