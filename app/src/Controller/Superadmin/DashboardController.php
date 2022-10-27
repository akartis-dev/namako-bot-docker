<?php

namespace App\Controller\Superadmin;

use App\Entity\ActionHistory;
use App\Entity\Customer;
use App\Entity\ErrorDetail;
use App\Entity\Message\UserMessages;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/superadmin")]
class DashboardController extends AbstractDashboardController
{
    #[Route("/", name: "superadmin")]
    public function index(): Response
    {
         return $this->render('superadmin/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('YoutubeBotSymfony')
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard("Dashboard", 'fa fa-home'),
            MenuItem::section('User'),
            MenuItem::linkToCrud('Customer', 'fa fa-user', Customer::class),

            MenuItem::section('Message'),
            MenuItem::linkToRoute('Send', 'fa fa-comment', 'send:message:user'),
            MenuItem::linkToCrud('Received', 'fa fa-comment-alt', UserMessages::class),

            MenuItem::section('Historic'),
            MenuItem::linkToCrud('User history', 'fa fa-list', ActionHistory::class),
            MenuItem::linkToCrud('History error', 'fa fa-close', ErrorDetail::class),
            MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        ];
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateTimeFormat('long', 'short')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorFetchJoinCollection(true)
            ->setPaginatorPageSize(20)
            ->setTimezone("Africa/Addis_Ababa")
            ;
    }
}
