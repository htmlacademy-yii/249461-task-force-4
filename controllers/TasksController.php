<?php

namespace app\controllers;

use app\models\Tasks;
use app\services\TaskCreateService;
use Yii;
use app\controllers\SecuredController;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\forms\TasksFilterForm;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\helpers\Url;

use app\services\TasksListFilterService;

class TasksController extends SecuredController
{

    private const TASKS_PER_PAGE = 5;

    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['add'],
            'roles' => ['@'],
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->identity->is_worker !== 0;
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    /*
     * Страница со списком новых тасков
     * */
    public function actionIndex()
    {
        $tasksListFilterService = new TasksListFilterService;
        $tasksFilterForm = new TasksFilterForm();

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
    public function actionView($id)
    {

        $task = Tasks::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Таск с ID $id не найден");
        }

        return $this->render('task', ['task' => $task]);
    }

    /*
     * Страница добавления таска
     * */
    public function actionAdd()
    {
        $newTask = new Tasks();

        if (Yii::$app->request->getIsPost()) {
            $newTask->load(Yii::$app->request->post());
            $newTask->author_id = Yii::$app->user->identity->id;

            if (Yii::$app->request->isAjax && $newTask->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($newTask);
            }

            if ($newTask->validate()) {

                $newTask->taskFilesList = UploadedFile::getInstances($newTask, 'taskFiles');

                $newTask->save(false);

                $taskCreateServices = new TaskCreateService();

                $taskCreateServices->saveFiles($taskCreateServices->uploadFiles($newTask->taskFilesList), $newTask->id);

                return $this->redirect('/tasks/view?id=' . $newTask->id);
            }
        }

        return $this->render('add-task', ['newTask' => $newTask]);
    }
}