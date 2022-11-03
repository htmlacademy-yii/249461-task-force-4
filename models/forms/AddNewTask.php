<?php

namespace app\models\forms;

use app\models\Categories;
use yii\base\Model;

class AddNewTask extends Model
{
    public $title;
    public $description;
    public $category_id;
    public $price;
    public $end_date;
    public $address;
    public $taskFiles;

    public function rules()
    {
        return [
            [['end_date'], 'safe'],
            [['title', 'description', 'category_id'], 'required'],
            [['description'], 'string'],
            [['category_id', 'price'], 'integer'],
            [['title', 'address'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['taskFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 0],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'price' => 'Бюджет',
            'end_date' => 'Срок исполнения',
            'address' => 'Локация',
            'taskFiles' => 'Файлы'
        ];
    }
}