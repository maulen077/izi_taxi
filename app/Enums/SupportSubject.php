<?php

namespace App\Enums;

enum SupportSubject: string
{
    case Complaint = 'complaint';
    case Technical = 'technical';
    case Payment = 'payment';
    case LostItem = 'lost-item';
    case Other = 'other';
}
