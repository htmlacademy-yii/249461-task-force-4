<?php

namespace app\services\task;

use Yii;
use app\models\Tasks;
use app\models\Users;

class TaskActionRefuse extends TaskAbstractAction
{

    protected $actionName = 'Отказаться';
    protected $actionSystemName = 'refuse';

    /**
     * @inheritDoc
     */
    function checkAvailable($task, ?int $currentUser) :bool
    {
        return $currentUser === $task->worker_id && $task->status === Tasks::STATUS_PROGRESS;
    }

    public function execute($task) {
        $task->status = Tasks::STATUS_FAILED;

        $user = Users::findOne($task->worker_id);
        $user->tasks_failed += 1;

        $transaction = Yii::$app->db->beginTransaction();

        if ($task->update() && $user->update()) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }
    }
}
