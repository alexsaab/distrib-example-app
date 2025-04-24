<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Setting')
            ->setEntityLabelInPlural('Settings')
            ->setSearchFields(['code', 'value', 'comment']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code')
                ->setRequired(true)
                ->setHelp('Unique identifier for the setting'),
            TextareaField::new('value')
                ->setRequired(false)
                ->setHelp('Value of the setting'),
            TextareaField::new('comment')
                ->setRequired(false)
                ->setHelp('Optional description of the setting'),
        ];
    }
} 