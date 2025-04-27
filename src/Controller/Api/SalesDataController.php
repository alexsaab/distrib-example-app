<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SalesDataController extends AbstractController
{
    #[Route('/api/salesdata/sales', name: 'api_salesdata_sales', methods: ['GET'])]
    public function sales(): JsonResponse
    {
        $data = file_get_contents($this->getParameter('kernel.project_dir') . '/templates/dataexamples/sales_mocking.json');
        return $this->json(json_decode($data, true));
    }

    #[Route('/api/salesdata/returns', name: 'api_salesdata_returns', methods: ['GET'])]
    public function returns(): JsonResponse
    {
        $data = file_get_contents($this->getParameter('kernel.project_dir') . '/templates/dataexamples/returns_mocking.json');
        return $this->json(json_decode($data, true));
    }

    #[Route('/api/salesdata/stocks', name: 'api_salesdata_stocks', methods: ['GET'])]
    public function stocks(): JsonResponse
    {
        $data = file_get_contents($this->getParameter('kernel.project_dir') . '/templates/dataexamples/stocks_mocking.json');
        return $this->json(json_decode($data, true));
    }
}