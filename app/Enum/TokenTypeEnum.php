<?php

declare(strict_types=1);

namespace App\Enum;

enum TokenTypeEnum: string
{
    case BEARER = 'bearer';
    case API_KEY = 'api-key';
    case LOGIN = 'login_password';
}
