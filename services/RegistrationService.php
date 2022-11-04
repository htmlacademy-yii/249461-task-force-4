<?php

namespace app\services;

use app\models\Users;
use Yii;

class RegistrationService
{
    private function getDefaultUserAvatar() {
        return "img/avatars/" . rand(1,5) . ".png";
    }

    public function saveNewUser($newUser) {
        $user = new Users();
        $user->name = $newUser->name;
        $user->email = $newUser->email;
        $user->password = Yii::$app->security->generatePasswordHash($newUser->password);
        $user->city_id = $newUser->city_id;
        $user->is_worker = $newUser->is_worker;
        $user->avatar = $this->getDefaultUserAvatar();

        $user->save(false);
    }
}