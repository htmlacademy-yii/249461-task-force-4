<?php

namespace app\models\forms;

use yii\base\Model;

class AddResponseForm extends Model
{
    public $comment;
    public $price;

    public function rules()
    {
        return [
            [['comment', 'price'], 'safe'],
            [['comment', 'price'], 'required'],
            ['comment', 'trim'],
            ['comment', 'string', 'min' => 10, 'max' => 512],
            ['price', 'integer', 'min' => 1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'comment' => 'Ваш комментарий',
            'price' => 'Стоимость'
        ];
    }
}