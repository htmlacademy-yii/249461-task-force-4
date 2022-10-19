<?php

namespace app\controllers;

use app\models\Users;
use yii\web\Controller;
use Yii;

class RegistrationController extends Controller
{
    public function actionIndex() {
        $newUser = new Users();

        if (Yii::$app->request->getIsPost()) {
            $newUser->load(Yii::$app->request->post());

            if ($newUser->validate()) {
                $newUser->password = Yii::$app->security->generatePasswordHash($newUser->password);

                $newUser->save(false);
                $this->goHome();
            }
        }

        return $this->render('index', ['newUser' => $newUser]);
    }
}