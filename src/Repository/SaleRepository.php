<?php

namespace App\Repository;

use App\Entity\Sale;
use App\Service\SettingsManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sale>
 */
class SaleRepository extends ServiceEntityRepository
{
    private $settingsManager;

    /**
     * Constructor
     * @param ManagerRegistry $registry
     * @param SettingsManager $settingsManager
     */
    public function __construct(ManagerRegistry $registry, SettingsManager $settingsManager)
    {
        parent::__construct($registry, Sale::class);
        $this->settingsManager = $settingsManager;
    }

    /**
     * Returns all sales with the following format
     * @param int $page
     * @return array<string, mixed>
     */
    public function findAllFormatted(int $page = 1): array
    {
        $perPage = $this->settingsManager->getInt('api_per_page', 10);
        $offset = ($page - 1) * $perPage;

        $qb = $this->createQueryBuilder('s')
            ->setFirstResult($offset)
            ->setMaxResults($perPage);

        $data = $qb->getQuery()->getResult();
        $total = $this->count([]);

        $formattedData = array_map(function ($item) {
            return [
                'taxId' => $item->getTaxId(),
                'brand' => $item->getBrand(),
                'sku' => $item->getSku(),
                'saleDate' => $item->getSalesDate()->format('Y-m-d'),
                'quantity' => $item->getQuantity()
            ];
        }, $data);

        return [
            "data" => $formattedData,
            "pagination" => [
                "total" => $total,
                "per_page" => $perPage,
                "current_page" => $page,
                "last_page" => ceil($total / $perPage)
            ]
        ];
    }
} 