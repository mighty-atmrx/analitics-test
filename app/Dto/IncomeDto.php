<?php

declare(strict_types=1);

namespace App\Dto;

use Carbon\CarbonImmutable;
use Exception;use Illuminate\Support\Carbon;

class IncomeDto implements BaseDto
{
    public function __construct(
        public int $account_id,
        public int $income_id,
        public ?string $number,
        public string $date,
        public string $last_change_date,
        public string $supplier_article,
        public string $tech_size,
        public int $barcode,
        public int $quantity,
        public float $total_price,
        public string $date_close,
        public string $warehouse_name,
        public int $nm_id,
        public CarbonImmutable $sync_date
    ){}

    public function toArray(): array
    {
        return [
            'account_id' => $this->account_id,
            'income_id' => $this->income_id,
            'number' => $this->number,
            'date' => $this->date,
            'last_change_date' => $this->last_change_date,
            'supplier_article' => $this->supplier_article,
            'tech_size' => $this->tech_size,
            'barcode' => $this->barcode,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'date_close' => $this->date_close,
            'warehouse_name' => $this->warehouse_name,
            'nm_id' => $this->nm_id,
            'sync_date' => $this->sync_date
        ];
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $item): BaseDto
    {
        return new self(
            account_id: (int) $item['account_id'],
            income_id: (int)($item['income_id'] ?? 0),
            number: !empty($item['number']) ? (string)$item['number'] : null,
            date: (string)($item['date'] ?? ''),
            last_change_date: (string)($item['last_change_date'] ?? ''),
            supplier_article: (string)($item['supplier_article'] ?? ''),
            tech_size: (string)($item['tech_size'] ?? ''),
            barcode: (int)($item['barcode'] ?? 0),
            quantity: (int)($item['quantity'] ?? 0),
            total_price: (float)($item['total_price'] ?? 0),
            date_close: (string)($item['date_close'] ?? ''),
            warehouse_name: (string)($item['warehouse_name'] ?? ''),
            nm_id: (int)($item['nm_id'] ?? 0),
            sync_date: Carbon::parse($item['sync_date'])->toImmutable(),
        );
    }
}
