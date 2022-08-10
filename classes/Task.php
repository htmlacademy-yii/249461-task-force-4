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
    private $mapStatus = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_COMPLETED => 'Выполнено',
        self::STATUS_PROGRESS => 'В работе',
        self::STATUS_FAILED => 'Провалено'
    ];

    /**
     * Список названий доступных действий
     */
    private $mapAction = [
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
    private $idExecutor;
    private $idClient;

    /**
     * Конструктор принимает id заказчика и исполнителя
     * @param int $idExecutor
     * @param int $idClient
     */
    public function __construct(int $idExecutor, int $idClient)
    {
        $this->idExecutor = $idExecutor;
        $this->idClient = $idClient;
    }


    /**
     * @return int ID исполнителя
     */
    public function getIdExecutor()
    {
        return $this->idExecutor;
    }


    /**
     * @return int ID клиента
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * @return string[] карту статусов
     */
    public function getMapStatus()
    {
        return $this->mapStatus;
    }


    /**
     * @param $action string Действие пользователя
     * @return string Название действия
     */
    public function getMapAction($action)
    {
        return $this->mapAction[$action];
    }

    /**
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
     * @param $currentStatus string Текущий статус задания
     * @param $id int Идентификатор пользователя
     * @return int|string|void Доступное действие с заданием, если оно доступно
     */
    public function getAvailableAction(string $currentStatus, int $id)
    {
        if ($id === self::getIdExecutor()) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return self::ACTION_RESPOND;
                case self::STATUS_PROGRESS:
                    return self::ACTION_REFUSE;
            }
        } elseif ($id === self::getIdClient()) {
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
}
