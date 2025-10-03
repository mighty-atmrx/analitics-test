<?php

namespace App\Dto;

use Exception;

class SaleDto implements BaseDto
{
    public function __construct(
        public string $g_number,
        public string $date,
        public string $last_change_date,
        public string $supplier_article,
        public string $tech_size,
        public int $barcode,
        public float $total_price,
        public int $discount_percent,
        public bool $is_supply,
        public bool $is_realization,
        public ?float $promo_code_discount,
        public string $warehouse_name,
        public string $country_name,
        public string $oblast_okrug_name,
        public string $region_name,
        public int $income_id,
        public string $sale_id,
        public ?string $odid,
        public int $spp,
        public float $for_pay,
        public float $finished_price,
        public float $price_with_disc,
        public int $nm_id,
        public string $subject,
        public string $category,
        public string $brand,
        public ?bool $is_storno,
    ){}

    public function toArray(): array
    {
        return [
            'g_number' => $this->g_number,
            'date' => $this->date,
            'last_change_date' => $this->last_change_date,
            'supplier_article' => $this->supplier_article,
            'tech_size' => $this->tech_size,
            'barcode' => $this->barcode,
            'total_price' => $this->total_price,
            'discount_percent' => $this->discount_percent,
            'is_supply' => $this->is_supply,
            'is_realization' => $this->is_realization,
            'promo_code_discount' => $this->promo_code_discount,
            'warehouse_name' => $this->warehouse_name,
            'country_name' => $this->country_name,
            'oblast_okrug_name' => $this->oblast_okrug_name,
            'region_name' => $this->region_name,
            'income_id' => $this->income_id,
            'sale_id' => $this->sale_id,
            'odid' => $this->odid,
            'spp' => $this->spp,
            'for_pay' => $this->for_pay,
            'finished_price' => $this->finished_price,
            'price_with_disc' => $this->price_with_disc,
            'nm_id' => $this->nm_id,
            'subject' => $this->subject,
            'category' => $this->category,
            'brand' => $this->brand,
            'is_storno' => $this->is_storno,
        ];
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $item): BaseDto
    {
        return new self(
            g_number: (string) ($item['g_number'] ?? ''),
            date: (string) ($item['date'] ?? ''),
            last_change_date: (string) ($item['last_change_date'] ?? ''),
            supplier_article: (string) ($item['supplier_article'] ?? ''),
            tech_size: (string) ($item['tech_size'] ?? ''),
            barcode: (int) ($item['barcode'] ?? 0),
            total_price: (float) ($item['total_price'] ?? 0),
            discount_percent: (int) ($item['discount_percent'] ?? 0),
            is_supply: (bool) ($item['is_supply'] ?? false),
            is_realization: (bool) ($item['is_realization'] ?? false),
            promo_code_discount: isset($item['promo_code_discount']) ? (float) $item['promo_code_discount'] : null,
            warehouse_name: (string) ($item['warehouse_name'] ?? ''),
            country_name: (string) ($item['country_name'] ?? ''),
            oblast_okrug_name: (string) ($item['oblast_okrug_name'] ?? ''),
            region_name: (string) ($item['region_name'] ?? ''),
            income_id: (int) ($item['income_id'] ?? 0),
            sale_id: (string) ($item['sale_id'] ?? ''),
            odid: isset($item['odid']) ? (string) $item['odid'] : null,
            spp: (int) ($item['spp'] ?? 0),
            for_pay: (float) ($item['for_pay'] ?? 0),
            finished_price: (float) ($item['finished_price'] ?? 0),
            price_with_disc: (float) ($item['price_with_disc'] ?? 0),
            nm_id: (int) ($item['nm_id'] ?? 0),
            subject: (string) ($item['subject'] ?? ''),
            category: (string) ($item['category'] ?? ''),
            brand: (string) ($item['brand'] ?? ''),
            is_storno: isset($item['is_storno']) ? (bool) $item['is_storno'] : null,
        );
    }
}
