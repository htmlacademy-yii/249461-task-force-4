<?php

require_once "vendor/autoload.php";

use TaskForce\classes\Task;
use TaskForce\classes\exceptions\TaskException;

use TaskForce\classes\SqlDataConverter;
use TaskForce\classes\ConverterCsvToSql;

$client = 222;
$executor = 111;
$request_executor = 333;
$default_task_status = 'new';

try {
    $newTask = new Task($default_task_status, $client);

    assert($newTask->getNextStatus('cancel') === Task::STATUS_CANCELED, 'cancel action');
    assert($newTask->getNextStatus('complete') === Task::STATUS_COMPLETED, 'complete action');
    assert($newTask->getNextStatus('respond') === Task::STATUS_NEW, 'respond action');
    assert($newTask->getNextStatus('refuse') === Task::STATUS_FAILED, 'refuse action');

    assert($newTask->getAvailableActions($request_executor)[0]->getActionSystemName() === 'respond', 'new status');

    assert($newTask->getAvailableActions($client)[0]->getActionSystemName() === 'cancel', 'cancel status');
    assert($newTask->getAvailableActions($client)[1]->getActionSystemName() === 'start', 'cancel status');

    $newTask->startTask($executor);

    assert($newTask->getAvailableActions($executor)[0]->getActionSystemName() === 'refuse', 'refuse status');
    assert($newTask->getAvailableActions($client)[0]->getActionSystemName() === 'complete', 'complete status');
} catch (TaskException $e) {
    echo "Error: " . $e->getMessage();
}


use TaskForce\classes\exceptions\FileConverterException;

$dataFilesDir = __DIR__ . '\data';
/*
 * Тут так сказать первая реализация которая начала работать на преобразование отдельно взятого файла
 * Затем понял что перемудрил с кол-вом передаваемой информации
 * */

/*$citiesFileName = 'cities.csv';
$citiesColumns = ['name','lat','lng'];
$citiesDbTable = 'cities';

$citiesConverter = new SqlDataConverter($citiesFileName, $dataFilesDir, $citiesColumns, $citiesDbTable);

try {
    $citiesConverter->convert();
} catch (FileConverterException $e) {
    echo $e->getMessage();
}

$categoriesFileName = 'categories.csv';
$categoriesColumns = ['name','icon'];
$categoriesDbTable = 'categories';

$categoriesConverter = new SqlDataConverter($categoriesFileName, $dataFilesDir, $categoriesColumns, $categoriesDbTable);

try {
    $categoriesConverter->convert();
} catch (FileConverterException $e) {
    echo $e->getMessage();
}*/

/*
 * Вторая версия конвертора, которая принимает только папку с файлами, сама отсортировывает
 * CSV файлы, и генерирует из них SQL файлы с INSERT
 * */

try {
    $converter = new ConverterCsvToSql($dataFilesDir);
    $converter->start();
} catch (FileConverterException $e) {
    echo $e->getMessage();
}

