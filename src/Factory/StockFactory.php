<?php

namespace App\Factory;

use App\Entity\Stock;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class StockFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Stock::class;
    }

    public function defaults(): array|callable
    {
        return [
            'brand'     => self::faker()->word(),
            'sku'       => self::faker()->bothify('SKU-####'),
            'stockDate' => self::faker()->dateTimeBetween('now', 'now'),
            'quantity'  => self::faker()->numberBetween(1, 100),
        ];
    }
}