<?php

require_once "vendor/autoload.php";

use TaskForce\classes\Task;

$client = 222;
$executor = 111;
$request_executor = 333;
$default_task_status = 'new';

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

