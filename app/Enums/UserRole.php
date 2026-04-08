<?php

namespace App\Enums;

enum UserRole: string
{
    case Passenger = 'passenger';
    case Driver = 'driver';
    case Dispatcher = 'dispatcher';
    case Admin = 'admin';
}
