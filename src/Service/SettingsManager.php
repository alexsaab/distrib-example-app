<?php

namespace App\Service;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;

class SettingsManager
{
    private $settingRepository;
    private $entityManager;

    public function __construct(
        SettingRepository $settingRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->settingRepository = $settingRepository;
        $this->entityManager = $entityManager;
    }

    public function get(string $code, $default = null): ?string
    {
        $setting = $this->settingRepository->findByCode($code);
        return $setting ? $setting->getValue() : $default;
    }

    public function set(string $code, ?string $value, ?string $comment = null): void
    {
        $setting = $this->settingRepository->findByCode($code);
        
        if (!$setting) {
            $setting = new Setting();
            $setting->setCode($code);
        }

        $setting->setValue($value);
        if ($comment !== null) {
            $setting->setComment($comment);
        }

        $this->entityManager->persist($setting);
        $this->entityManager->flush();
    }
} 