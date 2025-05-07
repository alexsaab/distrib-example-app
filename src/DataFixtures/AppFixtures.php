<?php

namespace App\DataFixtures;

use App\Factory\StockFactory;
use App\Factory\SaleFactory;
use App\Factory\ReturnDataFactory;
use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Settings
        $apiSecret = new Setting();
        $apiSecret->setCode('api_secret');
        $apiSecret->setValue('myApiSecret01');
        $apiSecret->setComment('Секретная фраза для API');
        $manager->persist($apiSecret);

        $apiPerPage = new Setting();
        $apiPerPage->setCode('api_per_page');
        $apiPerPage->setValue('10');
        $apiPerPage->setComment('Количество данных на странице API');
        $manager->persist($apiPerPage);

        $manager->flush();

        $projectDir = __DIR__ . '/../../templates/dataexamples';

        // Stocks
        $stocks = json_decode(file_get_contents($projectDir . '/stocks_mocking.json'), true);
        foreach ($stocks as $stock) {
            StockFactory::createOne([
                'brand' => $stock['brand'] ?? 'Miles',
                'sku' => $stock['sku'] ?? 'Unknown',
                'stockDate' => new \DateTime(),
                'quantity' => $stock['quantity'] ?? 0,
            ]);
        }

        // Sales
        $sales = json_decode(file_get_contents($projectDir . '/sales_mocking.json'), true);
        foreach ($sales as $sale) {
            SaleFactory::createOne([
                'taxId' => $sale['taxId'] ?? '123456789',
                'salesDate' => new \DateTime(),
                'brand' => $sale['brand'] ?? 'Miles',
                'sku' => $sale['sku'] ?? 'Unknown',
                'quantity' => $sale['quantity'] ?? 0,
            ]);
        }

        // Returns
        $returns = json_decode(file_get_contents($projectDir . '/returns_mocking.json'), true);
        foreach ($returns as $return) {
            ReturnDataFactory::createOne([
                'taxId' => $return['taxId'] ?? '123456789',
                'brand' => $return['brand'] ?? 'Miles',
                'sku' => $return['sku'] ?? 'Unknown',
                'salesDate' => new \DateTime(),
                'returnDate' => new \DateTime(),
                'quantity' => $return['quantity'] ?? 0,
            ]);
        }
    }
}
