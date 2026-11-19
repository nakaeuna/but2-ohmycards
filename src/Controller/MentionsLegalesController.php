<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MentionsLegalesController extends AbstractController
{
    #[Route('/mentionsLegales', name: 'app_mentions_legales')]
    public function index(): Response
    {
        return $this->render('mentionsLegales/mentionsLegales.html.twig');
    }
}
