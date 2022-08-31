<?php

namespace TaskForce\classes;

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
            case self::ACTION_START:
                return self::STATUS_PROGRESS;
            case self::ACTION_CANCEL:
                return self::STATUS_CANCELED;
            case self::ACTION_COMPLETE:
                return self::STATUS_COMPLETED;
            case self::ACTION_RESPOND:
                return self::STATUS_NEW;
            case self::ACTION_REFUSE:
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
                $actions = [self::ACTION_RESPOND];

                if ($currentUserId === $this->getClientId()) {
                    $actions = [self::ACTION_CANCEL, self::ACTION_START];
                }
                break;
            case self::STATUS_PROGRESS:
                if ($currentUserId === $this->getExecutorId()) {
                    $actions = [self::ACTION_REFUSE];
                }
                if ($currentUserId === $this->getClientId()) {
                    $actions = [self::ACTION_COMPLETE];
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
