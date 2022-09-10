<?php

require_once "vendor/autoload.php";

use TaskForce\classes\Task;

$client = 222;
$executor = 111;
$request_executor = null;
$default_task_status = 'new';

$newTask = new Task($default_task_status, $client);

assert($newTask->getNextStatus('cancel') === Task::STATUS_CANCELED, 'cancel action');
assert($newTask->getNextStatus('complete') === Task::STATUS_COMPLETED, 'complete action');
assert($newTask->getNextStatus('respond') === Task::STATUS_NEW, 'respond action');
assert($newTask->getNextStatus('refuse') === Task::STATUS_FAILED, 'refuse action');

assert($newTask->getAvailableActions('new', $request_executor)[0] === 'respond', 'new status');

$newTask->startTask($executor);

assert($newTask->getAvailableActions('progress', $executor)[0] === 'refuse', 'refuse status');
assert($newTask->getAvailableActions('progress', $client)[0] === 'complete', 'complete status');
assert($newTask->getAvailableActions('new', $client) === ['cancel', 'start'], 'cancel/start status');

