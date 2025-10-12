<?php

declare(strict_types=1);

namespace App\Enum;

enum ApiServiceEnum: string
{
    case WB = 'wb';
    case OZON = 'ozon';
    case YANDEX = 'yandex';
}
