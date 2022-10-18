<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Users;
use yii\db\ActiveRecord;

class UserRegistration extends Model
{

    public $name;
    public $email;
    public $city_id;
    public $password;
    public $password_retype;
    public $is_worker;

    public function rules()
    {
        return [
            [['name', 'email', 'password', 'password_retype', 'city_id'], 'safe'],
            [['name', 'email', 'password', 'password_retype', 'city_id'], 'required'],
            [['is_worker', 'city_id'], 'integer'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'string', 'max' => 128],
            [['password'], 'string', 'min' => 8, 'max' => 64],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_retype' => 'Повтор пароля',
            'is_worker' => 'я собираюсь откликаться на заказы',
            'city_id' => 'Город',
        ];
    }
}