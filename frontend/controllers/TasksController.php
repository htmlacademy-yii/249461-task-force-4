<?php

namespace frontend\controllers;

use app\models\Tasks;
use app\models\Categories;
use yii\web\Controller;

class TasksController extends Controller
{

    private const TASKS_PER_PAGE = 5;

    public function getTasks()
    {
        return Tasks::find()
            ->where(['status' => 'new'])
            ->joinWith(['category','city'])
            ->orderBy(['add_date'=> SORT_DESC])
            ->limit(self::TASKS_PER_PAGE)->all();
    }

    public function actionIndex()
    {
        $tasks = $this->getTasks();
        return $this->render('index', compact("tasks"));
    }
}