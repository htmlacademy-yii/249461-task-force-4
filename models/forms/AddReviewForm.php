<?php

namespace app\models\forms;

use yii\base\Model;

class AddReviewForm extends Model
{
    public $review;
    public $mark;

    public function rules()
    {
        return [
            [['review', 'mark'], 'safe'],
            [['review', 'mark'], 'required'],
            ['review', 'trim'],
            ['review', 'string', 'min' => 10, 'max' => 512],
            ['mark', 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    public function attributeLabels()
    {
        return [
            'review' => 'Ваш комментарий',
            'mark' => 'Оценка работы'
        ];
    }
}