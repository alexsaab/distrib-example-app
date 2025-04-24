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

#[Route('/admin')]
class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/', name: 'admin_dashboard')]
    public function index(): Response
    {
        // Option 1: Return to a blank dashboard
        return $this->render('admin/dashboard.html.twig');

        // Option 2: Redirect to user list (uncomment if you prefer this)
        // return $this->redirect(
        //     $this->adminUrlGenerator
        //         ->setController(UserCrudController::class)
        //         ->generateUrl()
        // );
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
        yield MenuItem::linkToCrud('Settings', 'fa fa-cog', Setting::class);
    }
} 