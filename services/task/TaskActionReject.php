<?php

namespace app\services\task;

use app\models\Responses;
use app\models\Tasks;

class TaskActionReject extends TaskAbstractAction
{

    protected $actionName = 'Отказать';
    protected $actionSystemName = 'reject';

    /**
     * @inheritDoc
     */
    function checkAvailable($task, ?int $currentUser) :bool
    {
        return $currentUser === $task->author_id && $task->status === Tasks::STATUS_NEW;
    }

    public function execute($response_id) {
        $response = Responses::findOne($response_id);
        $response->rejected = 1;
        $response->update();
    }
}