<?php

namespace App\Enums;

/**
 * Роли пользователей
 */
enum UserRole: string
{
    case USER = 'user';

    case ADMIN = 'admin';
}
