<?php

namespace TaskForce\classes\actions;

class ActionCancel extends AbstractAction
{
    /**
     * @inheritDoc
     */
    function getActionName() :string
    {
        return 'Отменить';
    }

    /**
     * @inheritDoc
     */
    function getActionSystemName() :string
    {
        return 'cancel';
    }

    /**
     * @inheritDoc
     */
    function userRoleCheck($currentUser, $clientId, $executorId) :bool
    {
        return $currentUser === $clientId;
    }
}
