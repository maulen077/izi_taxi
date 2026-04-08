<?php

namespace App\Enums;

enum RideStatus: string
{
    case Searching = 'searching';
    case Accepted = 'accepted';
    case Arriving = 'arriving';
    case PickedUp = 'picked-up';
    case InProgress = 'in-progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function passengerLabel(): string
    {
        return match ($this) {
            self::Searching => 'Ищем водителя...',
            self::Accepted => 'Водитель найден',
            self::Arriving => 'Водитель едет к вам',
            self::PickedUp => 'Пассажир в машине',
            self::InProgress => 'В пути',
            self::Completed => 'Поездка завершена',
            self::Cancelled => 'Поездка отменена',
        };
    }

    public function driverLabel(): string
    {
        return match ($this) {
            self::Searching => 'Новый заказ',
            self::Accepted => 'Едем к месту подачи',
            self::Arriving => 'Едем к месту подачи',
            self::PickedUp => 'Пассажир в машине',
            self::InProgress => 'В пути',
            self::Completed => 'Поездка завершена',
            self::Cancelled => 'Отменен',
        };
    }
}
