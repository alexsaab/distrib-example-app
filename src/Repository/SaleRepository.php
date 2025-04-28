<?php

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sale>
 */
class SaleRepository extends ServiceEntityRepository
{
    /**
     * Constructor
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    /**
     * Returns all sales with the following format
     * @return array<int, array<string, mixed>>
     */
    public function findAllFormatted(): array
    {
        $data = parent::findAll();
        $data = array_map(function ($item) {
            return [(int)$item->getId() => [
                'taxId' => $item->getTaxId(),
                'brand' => $item->getBrand(),
                'sku' => $item->getSku(),
                'saleDate' => $item->getSalesDate()->format('Y-m-d'),
                'quantity' => $item->getQuantity()
            ]];
        }, $data);
        return $data;
    }
} 