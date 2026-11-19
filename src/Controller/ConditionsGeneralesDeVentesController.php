<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConditionsGeneralesDeVentesController extends AbstractController
{
    #[Route('/CGDV', name: 'app_CGDV')]
    public function index(): Response
    {
        return $this->render('CGDV/CGDV.html.twig');
    }
}
