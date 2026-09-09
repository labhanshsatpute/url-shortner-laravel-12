<?php

namespace App\Enums;

enum InviteStatus: string
{
    case ACCEPT = 'ACCEPT';
    case REJECT = 'REJECT';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}