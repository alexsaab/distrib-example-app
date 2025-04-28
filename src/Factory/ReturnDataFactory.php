<?php

namespace App\Factory;

use App\Entity\ReturnData;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends ModelFactory<ReturnData>
 */
final class ReturnDataFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return ReturnData::class;
    }

    public function defaults(): array|callable
    {
        return [
            'taxId'      => self::faker()->numerify('###########'),
            'brand'      => self::faker()->word(),
            'sku'        => self::faker()->bothify('SKU-####'),
            'salesDate'  => self::faker()->dateTimeBetween('now', 'now'),
            'returnDate' => self::faker()->dateTimeBetween('-2 weeks', 'now'),
            'quantity'   => self::faker()->numberBetween(1, 10),
        ];
    }
} 