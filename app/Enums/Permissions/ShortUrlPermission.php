<?php

namespace App\Enums\Permissions;

enum ShortUrlPermission: string
{
    case SHORT_URL_CREATE = 'SHORT_URL_CREATE';
    case VIEW_ALL_SHORT_URLS = 'VIEW_ALL_SHORT_URLS';
    case VIEW_COMPANY_SHORT_URL = 'VIEW_COMPANY_SHORT_URL';
    case VIEW_SELF_SHORT_URL = 'VIEW_SELF_SHORT_URL';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}