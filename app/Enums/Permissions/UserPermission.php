<?php

namespace App\Enums\Permissions;

enum UserPermission: string
{
    case INVITE_USER_TO_ALL_COMPANY = 'INVITE_USER_TO_ALL_COMPANY';
    case VIEW_ALL_COMPANY_USERS = 'VIEW_ALL_COMPANY_USERS';
    case INVITE_USER_TO_SELF_COMPANY = 'INVITE_USER_TO_SELF_COMPANY';
    case VIEW_SELF_COMPANY_USERS = 'VIEW_SELF_COMPANY_USERS';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}