<?php

namespace App\Repository;

use App\Entity\ReturnData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReturnDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnData::class);
    }

    public function findAllFormatted(): array
    {
        $data = parent::findAll();
        $data = array_map(function ($item) {
            return [(int)$item->getId() => [
                'taxId' => $item->getTaxId(),
                'brand' => $item->getBrand(),
                'sku' => $item->getSku(),
                'quantity' => $item->getQuantity(),
                'salesDate' => $item->getSalesDate()->format('Y-m-d'),
                'returnDate' => $item->getReturnDate()->format('Y-m-d'),
            ]];
        }, $data);
        return $data;
    }
} 