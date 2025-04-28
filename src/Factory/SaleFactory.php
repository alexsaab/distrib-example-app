<?php

namespace App\Factory;

use App\Entity\Sale;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends ModelFactory<Sale>
 */
final class SaleFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Sale::class;
    }

    public function defaults(): array|callable
    {
        return [
            'taxId'     => self::faker()->numerify('###########'),
            'brand'     => self::faker()->word(),
            'sku'       => self::faker()->bothify('SKU-####'),
            'salesDate' => self::faker()->dateTimeBetween('now', 'now'),
            'quantity'  => self::faker()->numberBetween(1, 100),
        ];
    }
} 