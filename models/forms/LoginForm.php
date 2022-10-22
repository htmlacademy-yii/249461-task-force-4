<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Users;


class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user;

    public function rules()
    {
        return [
            [['email', 'password'], 'safe'],
            [['email', 'password'], 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль'
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password)) {
                $this->addError($attribute, 'Неправильный email или пароль.');
            }
        }
    }

    public function getUser()
    {
        if (empty($this->_user)) {
            $this->_user = Users::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }
}

