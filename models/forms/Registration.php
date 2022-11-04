<?php

namespace app\models\forms;

use app\models\Cities;
use yii\base\Model;

class Registration extends Model
{
    public $name;
    public $email;
    public $password;
    public $password_repeat;
    public $city_id;
    public $is_worker;

    public function rules()
    {
        return [
            [['name', 'email', 'password', 'password_repeat', 'city_id', 'is_worker'], 'safe'],
            [['name', 'email', 'password', 'password_repeat', 'city_id'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => '\app\models\Users'],
            [['email'], 'email'],
            [['password'], 'string', 'max' => 64],
            [['password'], 'compare'],
            [['is_worker', 'city_id'], 'integer'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'is_worker' => 'я собираюсь откликаться на заказы',
            'city_id' => 'Город',
        ];
    }
}