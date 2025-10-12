<?php

declare(strict_types=1);

namespace App\Dto;

use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Carbon;

class OrderDto implements BaseDto
{
    public function __construct(
        public int $account_id,
        public string $g_number,
        public ?DateTimeImmutable $date,
        public ?DateTimeImmutable $last_change_date,
        public string $supplier_article,
        public string $tech_size,
        public int $barcode,
        public float $total_price,
        public int $discount_percent,
        public string $warehouse_name,
        public string $oblast,
        public int $income_id,
        public string $odid,
        public int $nm_id,
        public string $subject,
        public string $category,
        public string $brand,
        public bool $is_cancel,
        public ?DateTimeImmutable $cancel_dt,
        public CarbonImmutable $sync_date
    ){}

    public function toArray(): array
    {
        return [
            'account_id' => $this->account_id,
            'g_number' => $this->g_number,
            'date' => $this->date,
            'last_change_date' => $this->last_change_date,
            'supplier_article' => $this->supplier_article,
            'tech_size' => $this->tech_size,
            'barcode' => $this->barcode,
            'total_price' => $this->total_price,
            'discount_percent' => $this->discount_percent,
            'warehouse_name' => $this->warehouse_name,
            'oblast' => $this->oblast,
            'income_id' => $this->income_id,
            'odid' => $this->odid,
            'nm_id' => $this->nm_id,
            'subject' => $this->subject,
            'category' => $this->category,
            'brand' => $this->brand,
            'is_cancel' => $this->is_cancel,
            'cancel_dt' => $this->cancel_dt,
            'sync_date' => $this->sync_date
        ];
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $item): self
    {
        return new self(
            account_id: (int) $item['account_id'],
            g_number: (string) ($item['g_number'] ?? ''),
            date: !empty($item['date']) ? new DateTimeImmutable($item['date']) : null,
            last_change_date: !empty($item['last_change_date']) ? new DateTimeImmutable($item['last_change_date']) : null,
            supplier_article: (string) ($item['supplier_article'] ?? ''),
            tech_size: (string) ($item['tech_size'] ?? ''),
            barcode: (int) ($item['barcode'] ?? 0),
            total_price: (float) ($item['total_price'] ?? 0),
            discount_percent: (int) ($item['discount_percent'] ?? 0),
            warehouse_name: (string) ($item['warehouse_name'] ?? ''),
            oblast: (string) ($item['oblast'] ?? ''),
            income_id: (int) ($item['income_id'] ?? 0),
            odid: (string) ($item['odid'] ?? '0'),
            nm_id: (int) ($item['nm_id'] ?? 0),
            subject: (string) ($item['subject'] ?? ''),
            category: (string) ($item['category'] ?? ''),
            brand: (string) ($item['brand'] ?? ''),
            is_cancel: (bool) ($item['is_cancel'] ?? false),
            cancel_dt: !empty($item['cancel_dt']) ? new DateTimeImmutable($item['cancel_dt']) : null,
            sync_date: Carbon::parse($item['sync_date'])->toImmutable(),
        );
    }
}
