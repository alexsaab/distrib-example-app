<?php

namespace App\Dto;

class StockDto
{
    public function __construct(
        public string $brand,
        public string $sku,
        public \DateTime $stockDate,
        public int $quantity
    ) {}
}