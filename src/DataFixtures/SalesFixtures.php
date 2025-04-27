<?php

namespace App\DataFixtures;

use App\Entity\Sale;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SaleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sale = new Sale();
        $sale->setProduct('Example Product');
        $sale->setQuantity(10);
        $sale->setAmount(99.99);
        $manager->persist($sale);

        $manager->flush();
    }
}