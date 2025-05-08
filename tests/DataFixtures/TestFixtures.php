<?php

namespace App\Tests\DataFixtures;

use App\Entity\Setting;
use App\Entity\Sale;
use App\Entity\ReturnData;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
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

        // Create test sales
        for ($i = 1; $i <= 15; $i++) {
            $sale = new Sale();
            $sale->setTaxId('1234567890' . $i);
            $sale->setBrand('Test Brand');
            $sale->setSku('TEST-SKU-' . $i);
            $sale->setSalesDate(new \DateTime());
            $sale->setQuantity($i * 10);
            $manager->persist($sale);
        }

        // Create test returns
        for ($i = 1; $i <= 15; $i++) {
            $return = new ReturnData();
            $return->setTaxId('1234567890' . $i);
            $return->setBrand('Test Brand');
            $return->setSku('TEST-SKU-' . $i);
            $return->setSalesDate(new \DateTime());
            $return->setReturnDate(new \DateTime());
            $return->setQuantity($i * 5);
            $manager->persist($return);
        }

        // Create test stocks
        for ($i = 1; $i <= 15; $i++) {
            $stock = new Stock();
            $stock->setBrand('Test Brand');
            $stock->setSku('TEST-SKU-' . $i);
            $stock->setStockDate(new \DateTime());
            $stock->setQuantity($i * 20);
            $manager->persist($stock);
        }

        $manager->flush();
    }
} 