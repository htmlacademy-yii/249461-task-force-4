<?php

set_include_path('classes');
spl_autoload_register();

$newTask = new Task(111,222);

assert($newTask->getNextStatus('cancel') === Task::STATUS_CANCELED, 'cancel action');
assert($newTask->getNextStatus('completed') === Task::STATUS_COMPLETED, 'completed action');
assert($newTask->getNextStatus('respond') === Task::STATUS_PROGRESS, 'respond action');
assert($newTask->getNextStatus('refuse') === Task::STATUS_FAILED, 'refuse action');

assert($newTask->getAvailableAction('new', 111) === Task::ACTION_RESPOND, 'progress status');
assert($newTask->getAvailableAction('progress', 111) === Task::ACTION_REFUSE, 'refuse status');
assert($newTask->getAvailableAction('new', 222) === Task::ACTION_CANCEL, 'respond status');
assert($newTask->getAvailableAction('progress', 222) === Task::ACTION_COMPLETED, 'completed status');
