<?php

namespace App\Enum;

enum SyncEndpointEnum: string
{
    case ORDERS = 'orders';
    case SALES = 'sales';
    case INCOMES = 'incomes';
    case STOCKS = 'stocks';
}
