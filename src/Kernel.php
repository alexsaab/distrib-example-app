<?php

namespace App;

use App\Doctrine\Type\ArrayType;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {
        parent::boot();

        if (!Type::hasType('array')) {
            Type::addType('array', ArrayType::class);
        }
    }
}
