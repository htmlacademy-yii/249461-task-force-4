<?php

namespace app\services\task;

use app\models\Reviews;
use app\models\Users;
use Yii;
use app\models\Tasks;

class TaskActionReview extends TaskAbstractAction
{

    protected $actionName = 'Выполнено';
    protected $actionSystemName = 'complete';

    /**
     * @inheritDoc
     */
    function checkAvailable($task, ?int $currentUser): bool
    {
        return $currentUser === $task->author_id && $task->status === Tasks::STATUS_PROGRESS;
    }

    public function execute($task, $newReview)
    {
        /*Новый отзыв*/
        $review = new Reviews();
        $review->task_id = $task->id;
        $review->review = $newReview->review;
        $review->mark = $newReview->mark;
        $review->author_id = $task->author_id;
        $review->worker_id = $task->worker_id;

        /*У таска новый статус*/
        $task->status = Tasks::STATUS_COMPLETED;

        /*У исполнителя + 1 выполненый таск*/
        $worker = Users::findOne($task->worker_id);
        $worker->tasks_completed = $worker->tasks_completed + 1;

        $transaction = Yii::$app->db->beginTransaction();

        if ($review->save() && $task->update()) {
            $worker->rating = $this->countRating($task->worker_id);
            if ($worker->update()) {
                $transaction->commit();
            }
        } else {
            $transaction->rollBack();
        }
    }

    /*
     * Пересчет рейтинга пользователя после добавления нового отзыва с новой оценкой
     */
    private function countRating($id)
    {
        $reviews = Reviews::find()->where(['worker_id' => $id])->all();
        $rating = 0;

        foreach ($reviews as $review) {
            $rating += $review['mark'];
        }

        $rating = round($rating / count($reviews), 2);

        return $rating;
    }
}
