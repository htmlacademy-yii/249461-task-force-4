<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $add_date
 * @property string $title
 * @property string $description
 * @property int $category_id
 * @property int|null $price
 * @property string|null $end_date
 * @property int $author_id
 * @property int|null $worker_id
 * @property string $status
 * @property string|null $address
 * @property int|null $city_id
 * @property float|null $lat
 * @property float|null $lng
 *
 * @property Users $author
 * @property Categories $category
 * @property Cities $city
 * @property Responses[] $responses
 * @property Reviews[] $reviews
 * @property TaskFiles[] $taskFiles
 * @property Users $worker
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['add_date', 'end_date'], 'safe'],
            [['title', 'description', 'category_id', 'author_id'], 'required'],
            [['description'], 'string'],
            [['category_id', 'price', 'author_id', 'worker_id', 'city_id'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['title', 'status', 'address'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['worker_id' => 'id']],
            [['taskFilesList'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 0],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'add_date' => 'Add Date',
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'price' => 'Бюджет',
            'end_date' => 'Срок исполнения',
            'author_id' => 'Author ID',
            'worker_id' => 'Worker ID',
            'status' => 'Status',
            'address' => 'Локация',
            'city_id' => 'City ID',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'taskFilesList' => 'Файлы'
        ];
    }

    /**
     * @var Файлы задачи
     */
    public $taskFilesList;

    /**
     * Gets query for [[Author]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Responses::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFiles::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Worker]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(Users::class, ['id' => 'worker_id']);
    }

    /**
     * Константы доступных статусов
     */
    const STATUS_NEW = 'new';               // новая
    const STATUS_CANCELED = 'canceled';     // отменена
    const STATUS_PROGRESS = 'progress';     // в работе
    const STATUS_COMPLETED = 'completed';   // выполнена
    const STATUS_FAILED = 'failed';         // провалена

    /**
     * Список названий доступных статусов
     */
    private function getStatusesList()
    {
        return [
                self::STATUS_NEW => 'Новое',
                self::STATUS_CANCELED => 'Отменено',
                self::STATUS_COMPLETED => 'Выполнено',
                self::STATUS_PROGRESS => 'В работе',
                self::STATUS_FAILED => 'Провалено'
            ];
    }

    /**
     * Название текущего статусов
     */
    public function getStatusName()
    {
        $statusList = $this->getStatusesList();
        return $statusList[$this->status];
    }
}
