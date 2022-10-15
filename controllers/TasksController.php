<?php

namespace app\controllers;

use app\models\Tasks;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\models\forms\TasksFilterForm;

class TasksController extends Controller
{

    private const TASKS_PER_PAGE = 5;

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

    public function actionIndex()
    {
        $tasksFilterForm = new TasksFilterForm();
        $tasksQuery = $this->getTasks();

        if ($tasksFilterForm->load(\Yii::$app->request->get())) {
            $this->filteredTasks($tasksQuery, $tasksFilterForm);
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => self::TASKS_PER_PAGE,
                'defaultPageSize' => self::TASKS_PER_PAGE,
            ],
            'query' => $tasksQuery,

        ]);
        return $this->render('index', ['dataProvider' => $dataProvider, 'tasksFilterForm' => $tasksFilterForm]);
    }

    public function actionView($id) {

        $task = Tasks::findOne($id);

        if(!$task) {
            throw new NotFoundHttpException("Таск с ID $id не найден");
        }

        return $this->render('task', ['task'=>$task]);
    }
}