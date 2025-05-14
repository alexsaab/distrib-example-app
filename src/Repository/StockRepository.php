<?php

namespace App\Repository;

use App\Entity\Stock;
use App\Service\SettingsManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stock>
 */
class StockRepository extends ServiceEntityRepository
{
    private $settingsManager;

    /**
     * Constructor
     * @param ManagerRegistry $registry
     * @param SettingsManager $settingsManager
     */
    public function __construct(ManagerRegistry $registry, SettingsManager $settingsManager)
    {
        parent::__construct($registry, Stock::class);
        $this->settingsManager = $settingsManager;
    }

    /**
     * Returns all stocks with the following format
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
                'brand' => $item->getBrand(),
                'sku' => $item->getSku(),
                'stockDate' => $item->getStockDate()->format('Y-m-d'),
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