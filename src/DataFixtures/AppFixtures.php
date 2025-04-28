<?php

namespace App\DataFixtures;

use App\Factory\StockFactory;
use App\Factory\SaleFactory;
use App\Factory\ReturnDataFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $projectDir = __DIR__ . '/../../templates/dataexamples';

        // Stocks
        $stocks = json_decode(file_get_contents($projectDir . '/stocks_mocking.json'), true);
        foreach ($stocks as $stock) {
            StockFactory::create([
                'product' => $stock['product'] ?? 'Unknown',
                'quantity' => $stock['quantity'] ?? 0,
            ]);
        }

        // Sales
        $sales = json_decode(file_get_contents($projectDir . '/sales_mocking.json'), true);
        foreach ($sales as $sale) {
            SaleFactory::create([
                'product' => $sale['product'] ?? 'Unknown',
                'quantity' => $sale['quantity'] ?? 0,
                'amount' => $sale['amount'] ?? 0.0,
            ]);
        }

        // Returns
        $returns = json_decode(file_get_contents($projectDir . '/returns_mocking.json'), true);
        foreach ($returns as $return) {
            ReturnDataFactory::create([
                'product' => $return['product'] ?? 'Unknown',
                'quantity' => $return['quantity'] ?? 0,
            ]);
        }
    }
}
