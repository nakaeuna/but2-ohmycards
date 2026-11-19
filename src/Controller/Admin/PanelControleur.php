<?php

namespace App\Controller\Admin;

use App\Entity\EntiteAvis;
use App\Entity\EntiteCommande;
use App\Entity\EntiteJeuxDeSociete;
use App\Entity\EntiteTCG;
use App\Entity\EntiteJeuxDeCartes;
use App\Entity\EntiteProduit;
use App\Entity\EntitePromo;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanelControleur extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProduitCrud::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sae Dev');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Accueil', 'fa fa-home', 'app_accueil');
        yield MenuItem::section('Produits');
        yield MenuItem::linkToCrud('Produit', 'fas fa-list', EntiteProduit::class);
        yield MenuItem::linkToCrud('Jeux De Carte', 'fas fa-list', EntiteJeuxDeCartes::class);
        yield MenuItem::linkToCrud('TCG', 'fas fa-list', EntiteTCG::class);
        yield MenuItem::linkToCrud('Jeux de société', 'fas fa-list', EntiteJeuxDeSociete::class);
        yield MenuItem::linkToCrud('Promo', 'fa-solid fa-cart-shopping', EntitePromo::class);
        yield MenuItem::section('Autres');
        yield MenuItem::linkToCrud('Commande', 'fas fa-list', EntiteCommande::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', Utilisateur::class);
        yield MenuItem::linkToCrud('Avis', 'fas fa-list', EntiteAvis::class);
        yield MenuItem::linkToLogout('Déconnections', 'fa-solid fa-door-open');
    }
}
