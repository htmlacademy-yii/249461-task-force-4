<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class Users extends ActiveRecord implements IdentityInterface
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
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
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
            'reg_date' => 'Дата регистрации',
            'name' => 'Ваше имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_retype' => 'Повтор пароля',
            'is_worker' => 'я собираюсь откликаться на заказы',
            'avatar' => 'Аватар',
            'birthday' => 'День рождения',
            'city_id' => 'Город',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'about_me' => 'Информация о себе',
            'show_contacts' => 'Показывать контакты',
            'tasks_completed' => 'Выполненые задачи',
            'tasks_failed' => 'Проваленный задачи',
            'rating' => 'Рейтинг',
        ];
    }

    public $password_retype;

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

    /*
     * Вход пользователя
     * */

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
