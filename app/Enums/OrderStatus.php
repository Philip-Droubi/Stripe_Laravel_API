<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DONE = 'done';
    case CANCEL = 'cancel';
    case PENDING = 'pending';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
