<?php

namespace App\Dto;

class SaleDto
{
    public function __construct(
        public string $taxId,
        public string $brand,
        public string $sku,
        public \DateTime $salesDate,
        public int $quantity
    ) {}
}