<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    public function findAllFormatted(): array
    {
        $data = parent::findAll();
        $data = array_map(function ($item) {
            return [(int)$item->getId() => [
                'brand' => $item->getBrand(),
                'sku' => $item->getSku(),
                'quantity' => $item->getQuantity()
            ]];
        }, $data);
        return $data;
    }
} 