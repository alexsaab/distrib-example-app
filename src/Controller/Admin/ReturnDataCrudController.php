<?php

namespace App\Controller\Admin;

use App\Entity\ReturnData;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReturnDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReturnData::class;
    }
}