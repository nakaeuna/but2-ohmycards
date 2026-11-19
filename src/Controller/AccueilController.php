<?php

namespace App\Controller;

use App\Entity\EntiteProduit;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('', name: 'app_accueil')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $products = $entityManager->getRepository(EntiteProduit::class)->findAll();

        return $this->render('accueil/accueil.html.twig', [
            'produits' => $products,
        ]);
    }
}
