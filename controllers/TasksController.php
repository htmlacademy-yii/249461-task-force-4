<?php

namespace app\controllers;

use app\models\Tasks;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

class TasksController extends Controller
{

    private const TASKS_PER_PAGE = 5;

    public function getTasks()
    {
        return new ActiveDataProvider([
            'query' => Tasks::find()
                ->where(['status' => 'new'])
                ->joinWith(['category','city'])
                ->orderBy(['add_date'=> SORT_DESC]),
            'pagination' => [
                'pageSize' => self::TASKS_PER_PAGE,
                'defaultPageSize' => self::TASKS_PER_PAGE,
            ],
        ]);
    }

    public function actionIndex()
    {
        $dataProvider = $this->getTasks();
        return $this->render('index', ['dataProvider'=>$dataProvider]);
    }


}