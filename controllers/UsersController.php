<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Users;

class UsersController  extends Controller
{
    public function actionView($id) {

        $user = Users::findOne($id);

        if(!$user) {
            throw new NotFoundHttpException("Пользователь с ID $id не найден");
        }

        return $this->render('user', ['user'=>$user]);
    }
}