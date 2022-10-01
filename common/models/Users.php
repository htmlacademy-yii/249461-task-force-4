<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $reg_date
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $is_worker
 * @property string|null $avatar
 * @property string|null $birthday
 * @property int|null $city_id
 * @property int|null $phone
 * @property string|null $telegram
 * @property string|null $about_me
 * @property int $show_contacts
 * @property int $tasks_completed
 * @property int $tasks_failed
 * @property float $rating
 *
 * @property Cities $city
 * @property Responses[] $responses
 * @property Reviews[] $authorReviews
 * @property Reviews[] $workerReviews
 * @property Tasks[] $authorTasks
 * @property Tasks[] $workerTasks
 * @property UserCategories[] $userCategories
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_date', 'birthday'], 'safe'],
            [['name', 'email', 'password'], 'required'],
            [['is_worker', 'city_id', 'phone', 'show_contacts', 'tasks_completed', 'tasks_failed'], 'integer'],
            [['about_me'], 'string'],
            [['rating'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 128],
            [['password', 'telegram'], 'string', 'max' => 64],
            [['avatar'], 'string', 'max' => 512],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reg_date' => 'Reg Date',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'is_worker' => 'Is Worker',
            'avatar' => 'Avatar',
            'birthday' => 'Birthday',
            'city_id' => 'City ID',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'about_me' => 'About Me',
            'show_contacts' => 'Show Contacts',
            'tasks_completed' => 'Tasks Completed',
            'tasks_failed' => 'Tasks Failed',
            'rating' => 'Rating',
        ];
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
        return $this->hasMany(Responses::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getAuthorReviews()
    {
        return $this->hasMany(Reviews::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getWorkerReviews()
    {
        return $this->hasMany(Reviews::class, ['worker_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getAuthorTasks()
    {
        return $this->hasMany(Tasks::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getWorkerTasks()
    {
        return $this->hasMany(Tasks::class, ['worker_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategories::class, ['user_id' => 'id']);
    }
}
