<?php

namespace app\controllers;

use app\models\forms\AddNewTask;
use app\models\forms\AddResponseForm;
use app\models\forms\AddReviewForm;
use app\models\Tasks;
use app\services\task\TaskActionCancel;
use app\services\task\TaskActionRefuse;
use app\services\task\TaskActionReject;
use app\services\task\TaskActionRespond;
use app\services\task\TaskActionReview;
use app\services\task\TaskActionStart;
use app\services\TaskCreateService;
use app\controllers\SecuredController;
use app\models\forms\TasksFilterForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\web\NotFoundHttpException;

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

                $task = new Tasks();
                $task->title = $newTask->title;
                $task->description = $newTask->description;
                $task->category_id = $newTask->category_id;
                $task->author_id = Yii::$app->user->identity->id;
                $task->price = $newTask->price;
                $task->end_date = $newTask->end_date;
                $task->address = $newTask->address;

                $task->save(false);

                $taskCreateServices = new TaskCreateService();
                /* Сохранение файлов таска */
                $taskCreateServices->saveUploadFiles($newTask->taskFiles, $task->id);

                return $this->redirect('/tasks/view?id=' . $task->id);
            }
        }

        return $this->render('add_task', ['newTask' => $newTask]);
    }

    /*
     * Откликнуться на задание
     * */
    public function actionRespond($id) {
        $newResponse = new AddResponseForm();
        $task = Tasks::findOne($id);

        /* Отклик исполнителя на задачу. */
        if (Yii::$app->request->getIsPost()) {
            $newResponse->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax && $newResponse->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($newResponse);
            }

            if ($newResponse->validate()){

                /* Сервисный класс отклика */
                $respond = new TaskActionRespond();

                /* Проверка прав пользователя через сервисный класс */
                if ($respond->checkAvailable($task, Yii::$app->user->identity->id)) {
                    $respond->execute($task->id, $newResponse, Yii::$app->user->identity->id);

                    return $this->redirect(['tasks/view/', 'id' => $task->id]);
                }
            }
        }
    }

    /*
     * Оставить отзыв исполнителю
     * */
    public function actionReview($id) {
        $newReview = new AddReviewForm();
        $task = Tasks::findOne($id);

        /* Закрытие таска, добавление отзыва исполнителю. */
        if (Yii::$app->request->getIsPost()) {
            $newReview->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax && $newReview->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($newReview);
            }

            $review = new TaskActionReview();

            if ($newReview->validate()){
                if ($review->checkAvailable($task, Yii::$app->user->identity->id)) {
                    $review->execute($task, $newReview);

                    return $this->redirect(['tasks/view/', 'id' => $id]);
                }
            }
        }
    }

    /**
     * Откзать исполнителю
     */
    public function actionReject($response_id, $task_id) {
        $reject = new TaskActionReject();
        $task = Tasks::findOne($task_id);

        if ($reject->checkAvailable($task, Yii::$app->user->identity->id)) {
            $reject->execute($response_id);

            return $this->redirect(['tasks/view/', 'id' => $task_id]);
        }
    }

    /**
     * Принять исполнителя
     */
    public function actionStart($id, $worker_id) {
        $task = Tasks::findOne($id);
        $start = new TaskActionStart();

        if ($start->checkAvailable($task, Yii::$app->user->identity->id)) {
            $start->execute($task, $worker_id);

            return $this->redirect(['tasks/view/', 'id' => $id]);
        }
    }

    /**
     * Отменить задание
     */
    public function actionCancel($id) {
        $task = Tasks::findOne($id);

        $cancel = new TaskActionCancel();

        if ($cancel->checkAvailable($task, Yii::$app->user->identity->id)) {
            $cancel->execute($task);
            return $this->redirect(['tasks/view/', 'id' => $id]);
        }
    }

    /**
     * Отказаться от задания
     */
    public function actionRefuse($id) {
        $task = Tasks::findOne($id);
        $refuse = new TaskActionRefuse();

        if ($refuse->checkAvailable($task, Yii::$app->user->identity->id)) {
            $refuse->execute($task);
            return $this->redirect(['tasks/view/', 'id' => $id]);
        }
    }
}