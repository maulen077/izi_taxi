<?php

namespace App\Enums;

enum DriverStatus: string
{
    case Offline = 'offline';
    case Online = 'online';
    case OrderReceived = 'order-received';
    case EnRouteToPickup = 'en-route-to-pickup';
    case PassengerWaiting = 'passenger-waiting';
    case InProgress = 'in-progress';

    public function label(): string
    {
        return match ($this) {
            self::Offline => 'Не на линии',
            self::Online => 'На линии',
            self::OrderReceived => 'Новый заказ',
            self::EnRouteToPickup => 'Едем к месту подачи',
            self::PassengerWaiting => 'Пассажир в машине',
            self::InProgress => 'В пути',
        };
    }
}
