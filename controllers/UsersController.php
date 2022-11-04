<?php

namespace app\controllers;

use app\models\forms\UserRegistration;
use app\controllers\SecuredController;
use app\models\Users;
use yii\web\NotFoundHttpException;

class UsersController  extends SecuredController
{
    public function actionView($id) {

        $user = Users::findOne($id);

        if(!$user) {
            throw new NotFoundHttpException("Пользователь с ID $id не найден");
        }

        return $this->render('user', ['user'=>$user]);
    }
}