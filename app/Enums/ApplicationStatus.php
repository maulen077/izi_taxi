<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'На рассмотрении',
            self::Approved => 'Одобрена',
            self::Rejected => 'Отклонена',
        };
    }
}
