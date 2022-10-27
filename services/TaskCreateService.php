<?php

namespace app\services;

use Yii;
use app\models\TaskFiles;

class TaskCreateService
{
    /**
     * @var string Путь к папке для загрузки файлов к таскам
     */
    private $basePath = 'uploads/';

    /**
     * @param $filesList array Загруженные файлы из формы
     * @return array С файлами уникальное серверное имя + имя файла
     */
    public function uploadFiles(array $filesList) {
        $uploadFiles = [];

        foreach ($filesList as $key => $file) {
            $fileName = $file->name;
            $fileServerName = uniqid($file->baseName). '.' . $file->extension;

            $file->saveAs($this->basePath . $fileServerName);

            $uploadFiles[$key] = ['name' => $fileName, 'path' =>  $fileServerName];
        }

        return $uploadFiles;
    }


    /**
     * @param array $files массив с данными файлов
     * @param int $taskId id таска
     * @return void
     */
    public function saveFiles(array $files, int $taskId)
    {
        foreach ($files as $file) {
            $taskFile = new TaskFiles();
            $taskFile->task_id = $taskId;
            $taskFile->path = $file['path'];
            $taskFile->name = $file['name'];
            $taskFile->save();
        }
    }

    /**
     * Функция форматирует размер файла нужный формат
     * @param $fileName string имя файла на сервере.
     * @return string|null
     */
    public function fileSize(string $fileName): ?string
    {
        $size = filesize(Yii::getAlias('@web'). $this->basePath . $fileName);
        return Yii::$app->formatter->asShortSize($size);
    }
}