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
        // Создаем настройки только если они не существуют
        $apiSecret = $manager->getRepository(Setting::class)->findOneBy(['code' => 'api_secret']);
        if (!$apiSecret) {
            $apiSecret = new Setting();
            $apiSecret->setCode('api_secret');
            $apiSecret->setValue('myApiSecret01');
            $apiSecret->setComment('Секретная фраза для API');
            $manager->persist($apiSecret);
        }

        $apiPerPage = $manager->getRepository(Setting::class)->findOneBy(['code' => 'api_per_page']);
        if (!$apiPerPage) {
            $apiPerPage = new Setting();
            $apiPerPage->setCode('api_per_page');
            $apiPerPage->setValue('10');
            $apiPerPage->setComment('Количество данных на странице API');
            $manager->persist($apiPerPage);
        }

        $manager->flush();

        $projectDir = __DIR__ . '/../../templates/dataexamples';

        // Stocks
        $stocks = json_decode(file_get_contents($projectDir . '/stocks_mocking.json'), true);
        foreach ($stocks as $stock) {
            StockFactory::createOne([
                'brand' => $stock[0] ?? 'Miles',
                'sku' => $stock[1] ?? 'Unknown',
                'stockDate' => isset($stock[2]) ? \DateTime::createFromFormat('Y-m-d', $stock[2]) : new \DateTime(),
                'quantity' => $stock[3] ?? 0,
            ]);
        }

        // Sales
        $sales = json_decode(file_get_contents($projectDir . '/sales_mocking.json'), true);
        foreach ($sales as $sale) {
            SaleFactory::createOne([
                'taxId' => $sale[0] ?? '123456789',
                'salesDate' => isset($sale[3]) ? \DateTime::createFromFormat('Y-m-d', $sale[3]) : new \DateTime(),
                'brand' => $sale[1] ?? 'Miles',
                'sku' => $sale[2] ?? 'Unknown',
                'quantity' => $sale[4] ?? 0,
            ]);
        }

        // Returns
        $returns = json_decode(file_get_contents($projectDir . '/returns_mocking.json'), true);
        foreach ($returns as $return) {            
            ReturnDataFactory::createOne([
                'taxId' => $return[0] ?? '123456789',
                'brand' => $return[1] ?? 'Miles',
                'sku' => $return[2] ?? 'Unknown',
                'salesDate' => isset($return[3]) ? \DateTime::createFromFormat('Y-m-d', $return[3]) : new \DateTime(),
                'returnDate' => isset($return[4]) ? \DateTime::createFromFormat('Y-m-d', $return[4]) : new \DateTime(),
                'quantity' => $return[5] ?? 0,
            ]);
        }
    }
}
