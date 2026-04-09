<?php

namespace App\Enums;

enum SupportSubject: string
{
    case Complaint = 'complaint';
    case Technical = 'technical';
    case Payment = 'payment';
    case LostItem = 'lost-item';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Complaint => 'Жалоба на водителя',
            self::Technical => 'Технические проблемы',
            self::Payment => 'Вопросы по оплате',
            self::LostItem => 'Забытые вещи',
            self::Other => 'Другое',
        };
    }
}
