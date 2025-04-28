<?php

namespace App\Controller\Admin;

use App\Entity\Sale;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SaleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sale::class;
    }
}