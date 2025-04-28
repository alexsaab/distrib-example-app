<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SaleRepository;
use App\Repository\ReturnDataRepository;
use App\Repository\StockRepository;

class SalesDataController extends AbstractController
{
    #[Route('/sales', name: 'api_salesdata_sales', methods: ['GET'])]
    public function sales(SaleRepository $saleRepository): JsonResponse
    {
        return $this->json($saleRepository->findAllFormatted(), 200);
    }

    #[Route('/returns', name: 'api_salesdata_returns', methods: ['GET'])]
    public function returns(ReturnDataRepository $returnDataRepository): JsonResponse
    {
        return $this->json($returnDataRepository->findAllFormatted(), 200);
    }

    #[Route('/stocks', name: 'api_salesdata_stocks', methods: ['GET'])]
    public function stocks(StockRepository $stockRepository): JsonResponse
    {
        return $this->json($stockRepository->findAllFormatted(), 200);
    }
}