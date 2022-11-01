<?php

namespace app\services;

use Yii;
use app\models\TaskFiles;

class TaskCreateService
{
    /**
     * @var string Путь к папке для загрузки файлов к таскам
     */
    private $basePath = 'uploads';

    /**
     * @param $filesList array Загруженные файлы из формы
     * @return array С файлами уникальное серверное имя + имя файла
     */
    private function serviceUploadFiles(array $filesList) {
        $uploadFiles = [];

        foreach ($filesList as $key => $file) {
            $fileName = $file->name;
            $fileServerName = uniqid($file->baseName). '.' . $file->extension;

            if (!is_dir($this->basePath)) {
                mkdir($this->basePath);
            }

            $file->saveAs($this->basePath . '/' . $fileServerName);

            $uploadFiles[$key] = ['name' => $fileName, 'path' =>  $fileServerName];
        }

        return $uploadFiles;
    }


    /**
     * @param array $files массив с данными файлов
     * @param int $taskId id таска
     * @return void
     */
    private function serviceSaveFiles(array $files, int $taskId)
    {
        foreach ($files as $file) {
            $taskFile = new TaskFiles();
            $taskFile->task_id = $taskId;
            $taskFile->path = $file['path'];
            $taskFile->name = $file['name'];
            $taskFile->save();
        }
    }

    public function saveUploadFiles($filesList, $task_id) {
        return $this->serviceSaveFiles($this->serviceUploadFiles($filesList), $task_id);
    }

    /**
     * Функция форматирует размер файла нужный формат
     * @param $fileName string имя файла на сервере.
     * @return string|null
     */
    public function showFileSize(string $fileName): ?string
    {
        $size = filesize(Yii::getAlias('@web'). $this->basePath . '/' . $fileName);
        return Yii::$app->formatter->asShortSize($size);
    }
}