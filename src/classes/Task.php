<?php

namespace TaskForce\classes;

use TaskForce\classes\actions\ActionCancel;
use TaskForce\classes\actions\ActionComplete;
use TaskForce\classes\actions\ActionRefuse;
use TaskForce\classes\actions\ActionRespond;
use TaskForce\classes\actions\ActionStart;

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
     * Константы доступных действий
     */
    const ACTION_START = 'start';           // начать
    const ACTION_CANCEL = 'cancel';         // отменить
    const ACTION_COMPLETE = 'complete';     // выполнена
    const ACTION_RESPOND = 'respond';       // откликнутся
    const ACTION_REFUSE = 'refuse';         // отказаться


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
     * Список названий доступных действий
     */
    const MAP_ACTION = [
        self::ACTION_START => 'Принять',
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_COMPLETE => 'Выполнено',
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_REFUSE => 'Отказаться',
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
     * @return int ID исполнителя
     */
    public function getExecutorId()
    {
        return $this->executorId;
    }


    /**
     * @return int ID клиента
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string[] карту статусов
     */
    public function getMapStatus()
    {
        return self::MAP_STATUS;
    }


    /**
     * @param $action string Действие пользователя
     * @return string Название действия
     */
    public function getMapAction($action)
    {
        if (!array_key_exists($action, self::MAP_ACTION)) {
            return 'Действие не существет';
        }
        return self::MAP_ACTION[$action];
    }

    /**
     * Метод для получения статуса, в которой он перейдёт после выполнения указанного действия
     * @param $action string Действие с заданием
     * @return string Статус задания
     */
    public function getNextStatus(string $action)
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
                return $this->status;
        }
    }

    /**
     * Метод для получения доступных действий для указанного статуса
     * @param string $status Текущий статус задания
     * @param ?int $currentUserId Идентификатор пользователя
     * @return array Доступное действие с заданием, если оно доступно
     */
    public function getAvailableActions(string $status, ?int $currentUserId)
    {
        $actions = [];

        switch ($status) {
            case self::STATUS_NEW:
                $actions = [$this->actionRespond->getActionSystemName()];

                if ($this->actionCancel->userRoleCheck($currentUserId, $this->clientId, $this->executorId)) {
                    $actions = [$this->actionCancel->getActionSystemName(), $this->actionStart->getActionSystemName()];
                }
                break;
            case self::STATUS_PROGRESS:
                if ($this->actionRefuse->userRoleCheck($currentUserId, $this->clientId, $this->executorId)) {
                    $actions = [$this->actionRefuse->getActionSystemName()];
                }
                if ($this->actionComplete->userRoleCheck($currentUserId, $this->clientId, $this->executorId)) {
                    $actions = [$this->actionComplete->getActionSystemName()];
                }
                break;
        }

        return $actions;
    }

    public function startTask(int $executorId)
    {
        if ($executorId === $this->clientId) {
            return print('Заказчик не может быть исполнителем');
        }

        $this->executorId = $executorId;
        $this->status = self::STATUS_PROGRESS;
    }
}
