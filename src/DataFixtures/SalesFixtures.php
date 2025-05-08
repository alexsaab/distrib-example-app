<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SalesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // This class exists only to satisfy Symfony's autoloading requirement
    }
}