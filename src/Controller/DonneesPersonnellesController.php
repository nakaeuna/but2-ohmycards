<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DonneesPersonnellesController extends AbstractController
{
    #[Route('/donneesPersonnelles', name: 'app_donnees_personnelles')]
    public function index(): Response
    {
        return $this->render('donneesPersonnelles/donneesPersonnelles.html.twig');
    }
}
