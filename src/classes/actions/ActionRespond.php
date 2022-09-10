<?php

namespace TaskForce\classes\actions;

class ActionRespond extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function getActionName()
    {
        return 'Откликнуться';
    }

    /**
     * @inheritDoc
     */
    function getActionSystemName()
    {
        return 'respond';
    }

    /**
     * @inheritDoc
     */
    function userRoleCheck($currentUser, $clientId, $executorId)
    {
        return $currentUser !== $clientId && $executorId !== null;
    }
}
