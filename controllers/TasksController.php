<?php

namespace app\controllers;

use app\models\Tasks;
use Yii;
use yii\web\Controller;
use app\controllers\SecuredController;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\models\forms\TasksFilterForm;

use app\services\TasksListFilterService;

class TasksController extends SecuredController
//class TasksController extends Controller
{

    private const TASKS_PER_PAGE = 5;

    /*
     * Страница со списком новых тасков
     * */
    public function actionIndex()
    {
        $tasksListFilterService = new TasksListFilterService;
        $tasksFilterForm        = new TasksFilterForm();

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => self::TASKS_PER_PAGE,
                'defaultPageSize' => self::TASKS_PER_PAGE,
            ],
            'query' => $tasksListFilterService->showTasks($tasksFilterForm),

        ]);
        return $this->render('index', ['dataProvider' => $dataProvider, 'tasksFilterForm' => $tasksFilterForm]);
    }

    /*
     * Страница просмотра таска
     * */
    public function actionView($id) {

        $task = Tasks::findOne($id);

        if(!$task) {
            throw new NotFoundHttpException("Таск с ID $id не найден");
        }

        return $this->render('task', ['task'=>$task]);
    }
}