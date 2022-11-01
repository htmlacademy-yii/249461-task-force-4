<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_files".
 *
 * @property int $id
 * @property int $task_id
 * @property string $path
 * @property string $name
 *
 * @property Tasks $task
 */
class TaskFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'path'], 'required'],
            [['task_id'], 'integer'],
            [['path'], 'string', 'max' => 512],
            [['name'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'path' => 'Path',
            'name' => 'File',
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
}
