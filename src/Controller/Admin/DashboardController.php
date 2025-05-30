<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Sale;
use App\Entity\ReturnData;
use App\Entity\Stock;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin Panel');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Sales', 'fa fa-shopping-cart', Sale::class);
        yield MenuItem::linkToCrud('Return Data', 'fa fa-arrow-left', ReturnData::class);
        yield MenuItem::linkToCrud('Stock', 'fa fa-box', Stock::class);
        yield MenuItem::linkToCrud('Settings', 'fa fa-cog', Setting::class);
    }
} 