<?php

namespace app\services;

use app\models\Tasks;
use GuzzleHttp\Client;
use Yii;
use app\models\TaskFiles;
use yii\helpers\ArrayHelper;

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

            $uploadFiles[$key] = ['name' => $fileName, 'path' => $this->basePath . '/' . $fileServerName];
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

    /*
     * Сохранение новой задачи в БД
     * */
    public function saveNewTask($newTask) {
        $task = new Tasks();
        $task->title = $newTask->title;
        $task->description = $newTask->description;
        $task->category_id = $newTask->category_id;
        $task->author_id = Yii::$app->user->identity->id;
        $task->price = $newTask->price;
        $task->end_date = $newTask->end_date;
        $task->address = $newTask->address;

        $task->save(false);
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
        $size = filesize(Yii::getAlias('@web'). $fileName);
        return Yii::$app->formatter->asShortSize($size);
    }


    /**
     * @param string $geocode Строка с адресом
     * @return array Пустой массив илиМассив с данными: город, адрес, координаты, если город совпадает с городом автора
     * @throws Exception
     */
    public function getGeocodeData(string $geocode): array
    {
        $apiKey = Yii::$app->params['geocoderApiKey'];
        $apiUri = 'https://geocode-maps.yandex.ru/1.x';
        $userCity = Yii::$app->user->identity->city->name;
        $result = [];
        $client = new Client();

        try {
            $response = $client->request('GET', $apiUri, [
                'query' => [
                    'geocode' => $geocode,
                    'apikey' => $apiKey,
                    'format' => 'json'
                ],
            ]);

            $content = $response->getBody()->getContents();
            $responseData = json_decode($content, true);

            $geoObjects = ArrayHelper::getValue($responseData, 'response.GeoObjectCollection.featureMember');

            foreach ($geoObjects as $geoObject) {
                $result[] = $this->getLocationData($geoObject);
            }
        } catch (GuzzleException $e) {
            $result = [];
        }

        return array_values(array_filter($result, fn($item) => $item['city'] === $userCity));
    }

    /**
     * @param array $geoObject объект ответа от геокодера
     * @return array Массив с данными, город, адрес, координаты
     * @throws Exception
     */
    public function getLocationData(array $geoObject): array
    {
        $geocoderMetaData = ArrayHelper::getValue($geoObject, 'GeoObject.metaDataProperty.GeocoderMetaData');
        $addressComponents = ArrayHelper::map(
            ArrayHelper::getValue($geocoderMetaData, 'Address.Components'),
            'kind',
            'name'
        );

        $location = ArrayHelper::getValue($geocoderMetaData, 'text');
        $city = ArrayHelper::getValue($addressComponents, 'locality');
        $address = ArrayHelper::getValue($geoObject, 'GeoObject.name');
        [$lng, $lat] = explode(' ', ArrayHelper::getValue($geoObject, 'GeoObject.Point.pos'));

        return [
            'location' => $location,
            'city' => $city,
            'address' => $address,
            'lat' => $lat,
            'lng' => $lng,
        ];
    }
}