<?php

namespace App\Enums;

enum TariffType: string
{
    case Economy = 'economy';
    case Comfort = 'comfort';
    case Business = 'business';
    case Minivan = 'minivan';

    public function label(): string
    {
        return match ($this) {
            self::Economy => 'Эконом',
            self::Comfort => 'Комфорт',
            self::Business => 'Бизнес',
            self::Minivan => 'Минивэн',
        };
    }
}
