<?php

set_include_path('classes');
spl_autoload_register();

$client = 222;
$executor = 111;

$newTask = new Task($client, $executor);

assert($newTask->getNextStatus('cancel') === Task::STATUS_CANCELED, 'cancel action');
assert($newTask->getNextStatus('completed') === Task::STATUS_COMPLETED, 'completed action');
assert($newTask->getNextStatus('respond') === Task::STATUS_PROGRESS, 'respond action');
assert($newTask->getNextStatus('refuse') === Task::STATUS_FAILED, 'refuse action');

assert($newTask->getAvailableAction('new', $executor) === Task::ACTION_RESPOND, 'progress status');
assert($newTask->getAvailableAction('progress', $executor) === Task::ACTION_REFUSE, 'refuse status');
assert($newTask->getAvailableAction('new', $client) === Task::ACTION_CANCEL, 'respond status');
assert($newTask->getAvailableAction('progress', $client) === Task::ACTION_COMPLETED, 'completed status');

$newTask->startTask($executor);

/*
 * Добрый вечер.
 * Предлагаю, чтоб после действия "Откликнуться на задачу", статус задачи не менялся.
 * И добавить действия "Запустить задачу" - это действие включает в себя выбор исполнителя и перевод задачи в статус "Выполняется"
 * */
