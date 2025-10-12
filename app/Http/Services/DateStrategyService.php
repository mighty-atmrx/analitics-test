<?php

namespace App\Http\Services;

use App\Enum\SyncEndpointEnum;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class DateStrategyService
{
    public function getDateRange(SyncEndpointEnum $endpoint, string $model, Account $account): array
    {
        return match($endpoint) {
            SyncEndpointEnum::STOCKS => $this->getStocksDateRange(),
            default => $this->getDefaultDateRange($model, $account),
        };
    }

    private function getStocksDateRange(): array
    {
        $today = date('Y-m-d');
        return ['from' => $today, 'to' => $today];
    }

    private function getDefaultDateRange(string $model, Account $account): array
    {
        /** @var class-string<Model> $model */
        $from = $model::query()
            ->where('account_id', $account['id'])
            ->max('sync_date') ?? '2000-01-01';

        return [
            'from' => $from,
            'to' => date('Y-m-d')
        ];
    }
}
