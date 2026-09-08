<?php

namespace App\Enums\Permissions;

enum CompanyPermission: string
{
    case COMPANY_CREATE = 'COMPANY_CREATE';
    case COMPANY_UPDATE = 'COMPANY_UPDATE';
    case COMPANY_DELETE = 'COMPANY_DELETE';
    case COMPANY_VIEW = 'COMPANY_VIEW';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}