<?php

namespace App\Enums;

enum TariffType: string
{
    case Economy = 'economy';
    case Comfort = 'comfort';
    case Business = 'business';
    case Minivan = 'minivan';
}
