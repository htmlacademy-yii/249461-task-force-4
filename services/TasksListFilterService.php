<?php

namespace app\services;

use app\models\Tasks;
use yii\db\Expression;

class TasksListFilterService {
    /**
     * Базовый запрос списка тасков
     * @return \yii\db\ActiveQuery
     */
    private function getTasks()
    {
        return Tasks::find()
            ->where(['status' => 'new'])
            ->joinWith(['category', 'city'])
            ->orderBy(['add_date' => SORT_DESC]);
    }

    /**
     * Если были выбраны фильтры, добавляются условия в выборку
     */
    private function filteredTasks($query, $filterForm)
    {
        if ($filterForm->categories) {
            $query->where(['in', 'category_id', $filterForm->categories]);
        }

        if ($filterForm->remoteWork) {
            $query->where(['is_remote' => $filterForm->remoteWork]);
        }

        if ($filterForm->withoutResponses) {
            $query->leftJoin('responses', 'tasks.id = responses.task_id')
                ->where(['is', 'task_id', null]);
        }

        switch ($filterForm->period) {
            case 'hour':
                $query->where(['>', 'tasks.add_date', new Expression('CURRENT_TIMESTAMP() - INTERVAL 1 HOUR')]);
                break;
            case 'day':
                $query->where(['>', 'tasks.add_date', new Expression('CURRENT_TIMESTAMP() - INTERVAL 1 DAY')]);
                break;
            case 'week':
                $query->where(['>', 'tasks.add_date', new Expression('CURRENT_TIMESTAMP() - INTERVAL 7 DAY')]);
                break;
        }

        return $query;
    }


    /*
     * Итоговый запрос на отрисовку списка тасков
     * */
    public function showTasks($form) {
        $tasks = $this->getTasks();

        if ($form->load(\Yii::$app->request->get())) {
            $this->filteredTasks($tasks, $form);
        }

        return $tasks;
    }
}
