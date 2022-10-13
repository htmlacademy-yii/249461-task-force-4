<?php

namespace app\models\forms;

use yii\base\Model;

class TasksFilterForm extends Model
{
    public $categories;
    public $withoutResponses;
    public $remoteWork;
    public $period;

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'withoutResponses' => 'Без откликов',
            'remoteWork' => 'Удалённая работа',
            'period' => 'Период'
        ];
    }

    public function rules()
    {
        return [
            [['categories', 'withoutResponses', 'remoteWork', 'period'], 'safe']
        ];
    }

    public static function getPeriodValue ()
    {
        return [
            'all' => 'За все время',
            'hour' => 'За один час',
            'day' => 'За сутки',
            'week' => 'За неделю',
        ];
    }
}