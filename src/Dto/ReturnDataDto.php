<?php

namespace App\Dto;

class ReturnDataDto
{
    public function __construct(
        public string $taxId,
        public string $brand,
        public string $sku,
        public \DateTime $salesDate,
        public \DateTime $returnDate,
        public int $quantity
    ) {}
}