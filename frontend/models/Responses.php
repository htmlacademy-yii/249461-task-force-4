<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "responses".
 *
 * @property int $id
 * @property string $add_date
 * @property int $task_id
 * @property int $user_id
 * @property string|null $comment
 * @property int|null $price
 * @property int $rejected
 *
 * @property Tasks $task
 * @property Users $user
 */
class Responses extends yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['add_date'], 'safe'],
            [['task_id', 'user_id'], 'required'],
            [['task_id', 'user_id', 'price', 'rejected'], 'integer'],
            [['comment'], 'string', 'max' => 512],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'comment' => 'Comment',
            'price' => 'Price',
            'rejected' => 'Rejected',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
