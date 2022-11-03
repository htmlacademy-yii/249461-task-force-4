<?php

namespace app\controllers;

use app\models\forms\AddNewTask;
use app\models\forms\AddResponseForm;
use app\models\forms\AddReviewForm;
use app\models\Responses;
use app\models\Tasks;
use app\models\Users;
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
use app\services\TaskViewServices;

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

        $newResponse = new AddResponseForm();
        $newReview = new AddReviewForm();
        $task = Tasks::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Таск с ID $id не найден");
        }

        /* Отклик исполнителя на задачу. */
        if (Yii::$app->request->getIsPost()) {
            $newResponse->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax && $newResponse->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($newResponse);
            }

            if ($newResponse->validate()){
                $taskViewServices = new TaskViewServices();
                $taskViewServices->addResponse($task->id, $newResponse);
            }
        }
        
        /* Закрытие таска, добавление отзыва исполнителю. */
        if (Yii::$app->request->getIsPost()) {
            $newReview->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax && $newReview->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($newReview);
            }

            if ($newReview->validate()){
                $taskViewServices = new TaskViewServices();
                $taskViewServices->addReview($task->id, $newReview);
            }
        }

        return $this->render('task', [
            'task' => $task,
            'newResponse' => $newResponse,
            'newReview' => $newReview,
        ]);
    }

    /*
     * Страница добавления таска
     * */
    public function actionAdd()
    {
        $newTask = new AddNewTask();

        if (Yii::$app->request->getIsPost()) {
            $newTask->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax && $newTask->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($newTask);
            }

            if ($newTask->validate()) {
                $newTask->taskFiles = UploadedFile::getInstances($newTask, 'taskFiles');

                $taskCreateServices = new TaskCreateService();
                /* Сохранение таска */
                $taskCreateServices->saveNewTask($newTask);
                /* Сохранение файлов таска */
                $taskCreateServices->saveUploadFiles($newTask->taskFiles, $task->id);

                return $this->redirect('/tasks/view?id=' . $task->id);
            }
        }

        return $this->render('add_task', ['newTask' => $newTask]);
    }

    /**
     * Откзать исполнителю
     */
    public function actionReject($id) {
        $response = Responses::findOne($id);
        $response->rejected = 1;
        $response->update();

        return $this->redirect(['tasks/view/', 'id' => $response->task_id]);
    }

    /**
     * Принять исполнителя
     */
    public function actionStart($id, $worker_id) {
        $taskStart = Tasks::findOne($id);
        $taskStart->status = Tasks::STATUS_PROGRESS;
        $taskStart->worker_id = $worker_id;
        $taskStart->update();

        return $this->redirect(['tasks/view/', 'id' => $id]);
    }

    /**
     * Отменить задание
     */
    public function actionCancel($id) {
        $taskCancel = Tasks::findOne($id);
        $taskCancel->status = Tasks::STATUS_CANCELED;
        $taskCancel->update();

        return $this->redirect(['tasks/view/', 'id' => $id]);
    }

    /**
     * Отказаться от задания
     */
    public function actionRefuse($id,$worker_id) {
        $task = Tasks::findOne($id);
        $task->status = Tasks::STATUS_FAILED;

        $user = Users::findOne($worker_id);
        $user->tasks_failed = $user->tasks_failed + 1;

        $transaction = Yii::$app->db->beginTransaction();

        if ($task->update() && $user->update()) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }

        return $this->redirect(['tasks/view/', 'id' => $id]);
    }
}