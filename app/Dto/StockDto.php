<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\SyncEndpointEnum;
use Exception;

class StockDto implements BaseDto
{
    public function __construct(
        public string $date,
        public ?string $last_change_date,
        public ?string $supplier_article,
        public ?string $tech_size,
        public int $barcode,
        public int $quantity,
        public ?bool $is_supply,
        public ?bool $is_realization,
        public ?int $quantity_full,
        public string $warehouse_name,
        public ?int $in_way_to_client,
        public ?int $in_way_from_client,
        public int $nm_id,
        public ?string $subject,
        public ?string $category,
        public ?string $brand,
        public ?int $sc_code,
        public ?float $price,
        public ?float $discount,
    ){}

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'last_change_date' => $this->last_change_date,
            'supplier_article' => $this->supplier_article,
            'tech_size' => $this->tech_size,
            'barcode' => $this->barcode,
            'quantity' => $this->quantity,
            'is_supply' => $this->is_supply,
            'is_realization' => $this->is_realization,
            'quantity_full' => $this->quantity_full,
            'warehouse_name' => $this->warehouse_name,
            'in_way_to_client' => $this->in_way_to_client,
            'in_way_from_client' => $this->in_way_from_client,
            'nm_id' => $this->nm_id,
            'subject' => $this->subject,
            'category' => $this->category,
            'brand' => $this->brand,
            'sc_code' => $this->sc_code,
            'price' => $this->price,
            'discount' => $this->discount,
        ];
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $item): BaseDto
    {
        return new self(
            date: (string) ($item['date'] ?? ''),
            last_change_date: isset($item['last_change_date']) ? (string) $item['last_change_date'] : null,
            supplier_article: isset($item['supplier_article']) ? (string) $item['supplier_article'] : null,
            tech_size: isset($item['tech_size']) ? (string) $item['tech_size'] : null,
            barcode: (int) ($item['barcode'] ?? 0),
            quantity: (int) ($item['quantity'] ?? 0),
            is_supply: isset($item['is_supply']) ? (bool) $item['is_supply'] : null,
            is_realization: isset($item['is_realization']) ? (bool) $item['is_realization'] : null,
            quantity_full: isset($item['quantity_full']) ? (int) $item['quantity_full'] : null,
            warehouse_name: (string) ($item['warehouse_name'] ?? ''),
            in_way_to_client: isset($item['in_way_to_client']) ? (int) $item['in_way_to_client'] : null,
            in_way_from_client: isset($item['in_way_from_client']) ? (int) $item['in_way_from_client'] : null,
            nm_id: (int) ($item['nm_id'] ?? 0),
            subject: isset($item['subject']) ? (string) $item['subject'] : null,
            category: isset($item['category']) ? (string) $item['category'] : null,
            brand: isset($item['brand']) ? (string) $item['brand'] : null,
            sc_code: isset($item['sc_code']) ? (int) $item['sc_code'] : null,
            price: isset($item['price']) ? (float) $item['price'] : null,
            discount: isset($item['discount']) ? (float) $item['discount'] : null,
        );
    }
}
