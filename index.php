<?php

set_include_path('classes');
spl_autoload_register();

$client = 222;
$executor = 111;

$newTask = new Task($client);

$newTask->startTask($executor);

assert($newTask->getNextStatus('cancel') === Task::STATUS_CANCELED, 'cancel action');
assert($newTask->getNextStatus('complete') === Task::STATUS_COMPLETED, 'complete action');
assert($newTask->getNextStatus('respond') === Task::STATUS_NEW, 'respond action');
assert($newTask->getNextStatus('refuse') === Task::STATUS_FAILED, 'refuse action');

assert($newTask->getAvailableActions('new', $executor)[0] === Task::ACTION_RESPOND, 'progress status');
assert($newTask->getAvailableActions('progress', $executor)[0] === Task::ACTION_REFUSE, 'refuse status');
assert($newTask->getAvailableActions('progress', $client)[0] === Task::ACTION_COMPLETE, 'complete status');
assert($newTask->getAvailableActions('new', $client) === [Task::ACTION_CANCEL, Task::ACTION_START], 'respond status');

