<?php

namespace App\Controller\Api;

use App\Service\SettingsManager;
use App\Trait\ApiSecretTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SaleRepository;
use App\Repository\ReturnDataRepository;
use App\Repository\StockRepository;

class SalesDataController extends AbstractController
{
    use ApiSecretTrait;

    #[Route('/sales', name: 'api_salesdata_sales', methods: ['GET'])]
    public function sales(Request $request, SaleRepository $saleRepository, SettingsManager $settingsManager): JsonResponse
    {
        $secret = $request->query->get('secret');
        if (!$secret) {
            return new JsonResponse(['error' => 'API secret is required'], 400);
        }

        $error = $this->checkApiSecret($secret, $settingsManager);
        if ($error) {
            return $error;
        }

        $page = (int)$request->query->get('page', 1);
        return $this->json($saleRepository->findAllFormatted($page), 200);
    }

    #[Route('/returns', name: 'api_salesdata_returns', methods: ['GET'])]
    public function returns(Request $request, ReturnDataRepository $returnDataRepository, SettingsManager $settingsManager): JsonResponse
    {
        $secret = $request->query->get('secret');
        if (!$secret) {
            return new JsonResponse(['error' => 'API secret is required'], 400);
        }

        $error = $this->checkApiSecret($secret, $settingsManager);
        if ($error) {
            return $error;
        }

        $page = (int)$request->query->get('page', 1);
        return $this->json($returnDataRepository->findAllFormatted($page), 200);
    }

    #[Route('/stocks', name: 'api_salesdata_stocks', methods: ['GET'])]
    public function stocks(Request $request, StockRepository $stockRepository, SettingsManager $settingsManager): JsonResponse
    {
        $secret = $request->query->get('secret');
        if (!$secret) {
            return new JsonResponse(['error' => 'API secret is required'], 400);
        }

        $error = $this->checkApiSecret($secret, $settingsManager);
        if ($error) {
            return $error;
        }

        $page = (int)$request->query->get('page', 1);
        return $this->json($stockRepository->findAllFormatted($page), 200);
    }
}