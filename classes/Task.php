<?php

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
    const ACTION_CANCEL = 'cancel';         // отменить
    const ACTION_COMPLETED = 'completed';   // выполнена
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
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_COMPLETED => 'Выполнено',
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_REFUSE => 'Отказаться',
    ];

    /**
     * Текущий статус задачи
     */
    public $currentStatus = self::STATUS_NEW;

    /**
     * ID заказчика и исполнителя
     */
    private $executorId;
    private $clientId;


    /**
     * Конструктор принимает id заказчика и исполнителя
     * @param int $clientId
     * @param int|null $executorId
     */
    public function __construct(int $clientId, int $executorId = null)
    {
        $this->executorId = $executorId;
        $this->clientId = $clientId;
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
     * @return mixed|string Статус задания
     */
    public function getNextStatus(string $action)
    {
        switch ($action) {
            case self::ACTION_CANCEL:
                return self::STATUS_CANCELED;
            case self::ACTION_COMPLETED:
                return self::STATUS_COMPLETED;
            case self::ACTION_RESPOND:
                return self::STATUS_PROGRESS;
            case self::ACTION_REFUSE:
                return self::STATUS_FAILED;
            default:
                return $this->currentStatus;
        }
    }

    /**
     * Метод для получения доступных действий для указанного статуса
     * @param $currentStatus string Текущий статус задания
     * @param $id int Идентификатор пользователя
     * @return int|string|void Доступное действие с заданием, если оно доступно
     */
    public function getAvailableAction(string $currentStatus, int $id)
    {
        if ($id === self::getExecutorId()) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return self::ACTION_RESPOND;
                case self::STATUS_PROGRESS:
                    return self::ACTION_REFUSE;
            }
        } elseif ($id === self::getClientId()) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return self::ACTION_CANCEL;
                case self::STATUS_PROGRESS:
                    return self::ACTION_COMPLETED;
            }
        } else {
            return print('Действие или пользователь не определены');
        }
    }

    public function startTask(int $executorId) {
        if ($executorId === $this->clientId) {
            return print('Заказчик не может быть исполнителем');
        }

        $this->executorId = $executorId;
        $this->currentStatus = self::STATUS_PROGRESS;
    }
}
