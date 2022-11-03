<?php

namespace app\controllers;

use app\models\forms\Registration;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\RegistrationService;
use Yii;

class RegistrationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $newUser = new Registration();

        if (Yii::$app->request->getIsPost()) {
            $newUser->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax && $newUser->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($newUser);
            }

            if ($newUser->validate()){

                $registrationService = new RegistrationService();
                $registrationService->saveNewUser($newUser);

                $this->goHome();
            }
        }

        return $this->render('index', ['newUser' => $newUser]);
    }
}