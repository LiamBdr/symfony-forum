<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect(
            $adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl()
        );

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FoRhum Dashboard')
            ->setTitle('<img alt="Logo FoRhum" style="height: 40px; margin-right: 5px" src="images/favicon-svg.svg">
                             <span style="font-weight: 700">FoRhum</span>')
            ->setFaviconPath('images/favicon-svg.svg');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),

            MenuItem::subMenu('Blog', 'fa fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('Liste des articles', 'fa fa-newspaper', Article::class),
                MenuItem::linkToCrud('Ajouter un article', 'fa fa-pen', Article::class)
                    ->setAction('new'),
                MenuItem::linkToCrud('Catégories', 'fa fa-list', Category::class),
            ]),

            MenuItem::linkToCrud('Commentaires', 'fa fa-comment', Comment::class),

            MenuItem::section(),
            MenuItem::linkToRoute('Retour au site', 'fa fa-door-open', 'app_home'),
//            MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        ];

    }
}
