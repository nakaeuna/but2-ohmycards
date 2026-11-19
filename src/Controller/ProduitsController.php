<?php

namespace App\Controller;

use App\Controller\Admin\AvisCrud;
use App\Controller\Recherche\RechercheData;
use App\Entity\EntiteAvis;
use App\Entity\EntiteProduit;
use App\Entity\Panier;
use App\Entity\ProduitsDansLePanier;
use App\Entity\Utilisateur;
use App\Form\AvisType;
use App\Form\categorie;
use App\Form\theme;
use App\Form\TrieType;
use App\Form\RegistrationFormType;
use App\Service\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\matches;

class ProduitsController extends AbstractController
{
    public function __construct(private CartManager $cartManager)
    {
    }

    #[Route('/produits', name: 'app_produits')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Formulaire de tri
        $data = new RechercheData();
        $data->page = $request->get('page', 1);
        $data->nom = $request->get('recherche', $data->nom);
        $data->theme = theme::from($request->get('themeu', theme::TOUS->value));
        $trieForm = $this->createForm(TrieType::class, $data);
        $trieForm->handleRequest($request);
        $produits = $entityManager->getRepository(EntiteProduit::class)->findSearch($data);

        return $this->render('produits/produits.html.twig', [
            'produits' => $produits,
            'form' => $trieForm,
        ]);
    }

    #[Route('/panier/ajouter', methods: ['POST'])]
    public function ajouterAuPanier(Request $request): Response
    {
        $data = $request->request->all();
        $id_produit = $data['id_produit'];
        $quantite = $data['quantite'];

        $this->cartManager->add($id_produit, $quantite);

        return $this->redirect("/panier");
    }

    #[Route('/panier/plus', methods: ['POST'], name: 'app_panier_plus')]
    public function plus(Request $request): Response
    {
        $data = $request->request->all();
        $id_produit = $data['id_produit'];

        $this->cartManager->plus($id_produit, 1);

        return $this->redirect("/panier");
    }

    #[Route('/panier/moins', methods: ['POST'], name: 'app_panier_moins')]
    public function moins(Request $request): Response
    {
        $data = $request->request->all();
        $id_produit = $data['id_produit'];

        $this->cartManager->plus($id_produit, -1);

        return $this->redirect("/panier");
    }

    #[Route('/panier/supprimer', name: 'app_produits_supprimer', methods: ['POST'])]
    public function supprimerDuPanier(Request $request): Response
    {
        $data = $request->request->all();
        $id_produit = $data['id_produit'];

        $this->cartManager->delete($id_produit);

        return $this->redirect("/panier");
    }

    #[Route('/produit/{id}', name: 'app_produit')]
    public function produit(int $id, EntityManagerInterface $entityManager, Request $request, Security $security): Response
    {
        $produit = $entityManager->getRepository(EntiteProduit::class)->find($id);

        if ($produit == null) {
            $route = $request->headers->get('referer');
            if (empty($route)) {
                return $this->redirectToRoute('app_produits');
            }
            return $this->redirect($route);
        }

        $form = $this->createForm(AvisType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $security->getUser();

            if (empty($utilisateur)) {
                return $this->render('produits/produit.html.twig', ['produit' => $produit, 'form' => $form->createView()]);
            }

            $avis = (new EntiteAvis())
                ->setAvis($form->get('avis')->getData())
                ->setProduit($produit)
                ->setUtilisateur($utilisateur)
                ->setNote($form->get('note')->getData());

            $entityManager->persist($avis);
            $entityManager->flush();
        }

        return $this->render('produits/produit.html.twig', ['produit' => $produit, 'form' => $form->createView()]);
    }
}
