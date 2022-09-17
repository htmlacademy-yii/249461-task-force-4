<?php

namespace TaskForce\classes;

use TaskForce\classes\actions\ActionCancel;
use TaskForce\classes\actions\ActionComplete;
use TaskForce\classes\actions\ActionRefuse;
use TaskForce\classes\actions\ActionRespond;
use TaskForce\classes\actions\ActionStart;
use TaskForce\classes\exceptions\TaskException;

class Task
{
    /**
     * Константы доступных статусов
     */
    const STATUS_NEW = 'new';               // новая
    const STATUS_CANCELED = 'canceled';     // отменена
    const STATUS_PROGRESS = 'progress';     // в работе
    const STATUS_COMPLETED = 'completed';   // выполнена
    const STATUS_FAILED = 'failed';         // провалена

    /**
     * Список названий доступных статусов
     */
    const MAP_STATUS = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_COMPLETED => 'Выполнено',
        self::STATUS_PROGRESS => 'В работе',
        self::STATUS_FAILED => 'Провалено'
    ];

    /**
     * Текущий статус задачи
     */
    public $status;

    /**
     * ID заказчика и исполнителя
     */
    private $executorId;
    private $clientId;

    /**
     * Объекты классов действий
     */
    public $actionCancel;
    public $actionComplete;
    public $actionRespond;
    public $actionRefuse;
    public $actionStart;


    /**
     * Конструктор принимает id заказчика и исполнителя
     * @param string $status
     * @param int $clientId
     * @param int|null $executorId
     */
    public function __construct(string $status, int $clientId, ?int $executorId = null)
    {
        $this->clientId = $clientId;
        $this->status = $status;
        $this->executorId = $executorId;

        $this->actionCancel = new ActionCancel();
        $this->actionComplete = new ActionComplete();
        $this->actionRespond = new ActionRespond();
        $this->actionRefuse = new ActionRefuse();
        $this->actionStart = new ActionStart();
    }


    /**
     * Возвращает ID  исполнителя
     * @return ?int ID исполнителя
     */
    public function getExecutorId() :?int
    {
        return $this->executorId;
    }


    /**
     * Возвращает ID  клиента
     * @return int ID клиента
     */
    public function getClientId() :int
    {
        return $this->clientId;
    }

    /**
     * Возвращает массив всех статусов для задания
     * @return string[] карту статусов
     */
    public function getMapStatus() :array
    {
        return self::MAP_STATUS;
    }

    /**
     * Метод для получения статуса, в которой он перейдёт после выполнения указанного действия
     * @param $action string Действие с заданием
     * @return string Статус задания
     * @throws TaskException Ошибка если указано недопустимое действие
     */
    public function getNextStatus(string $action) :string
    {
        switch ($action) {
            case $this->actionStart->getActionSystemName():
                return self::STATUS_PROGRESS;
            case $this->actionCancel->getActionSystemName():
                return self::STATUS_CANCELED;
            case $this->actionComplete->getActionSystemName():
                return self::STATUS_COMPLETED;
            case $this->actionRespond->getActionSystemName():
                return self::STATUS_NEW;
            case $this->actionRefuse->getActionSystemName():
                return self::STATUS_FAILED;
            default:
                throw new TaskException("Указано неверное действие");
        }
    }

    /**
     * Метод для получения доступных действий для указанного статуса
     * @param ?int $currentUserId Идентификатор пользователя
     * @return array Доступное действие с заданием, если оно доступно
     * @throws TaskException Ошибка если нет доступных действий
     */
    public function getAvailableActions(?int $currentUserId) :array
    {
        $actions = [];

        if ($this->actionCancel->checkAvailable($this, $currentUserId)) {
            $actions[] = $this->actionCancel;
        }
        if ($this->actionComplete->checkAvailable($this, $currentUserId)) {
            $actions[] = $this->actionComplete;
        }
        if ($this->actionRespond->checkAvailable($this, $currentUserId)) {
            $actions[] = $this->actionRespond;
        }
        if ($this->actionRefuse->checkAvailable($this, $currentUserId)) {
            $actions[] = $this->actionRefuse;
        }
        if ($this->actionStart->checkAvailable($this, $currentUserId)) {
            $actions[] = $this->actionStart;
        }

        if (empty($actions)) {
            throw new TaskException('Нет доступных действий');
        }

        return $actions;
    }

    /**
     * @param int $executorId Присвоение ID исполнителя
     * @return void
     * @throws TaskException Текст ошибки
     */
    public function startTask(int $executorId) :void
    {
        if ($executorId === $this->clientId) {
            throw new TaskException('Заказчик не может быть исполнителем');
        }

        if ($this->status !== self::STATUS_NEW) {
            throw new TaskException('Статус таска или добавлен исполнитель не может быть изменен');
        }

        $this->executorId = $executorId;
        $this->status = self::STATUS_PROGRESS;
    }
}
