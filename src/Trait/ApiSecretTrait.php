<?php

namespace App\Trait;

use App\Service\SettingsManager;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiSecretTrait
{
    private function checkApiSecret(string $secret, SettingsManager $settingsManager): ?JsonResponse
    {
        $apiSecret = $settingsManager->get('api_secret');
        if (!$apiSecret) {
            return new JsonResponse(['error' => 'API secret not configured'], 500);
        }

        if (md5($apiSecret) !== $secret) {
            return new JsonResponse(['error' => 'Invalid API secret'], 403);
        }

        return null;
    }
} 