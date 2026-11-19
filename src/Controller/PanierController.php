<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\EntiteAdresse;
use App\Entity\EntiteCommande;
use App\Entity\Utilisateur;
use App\Form\InviteType;
use App\Form\LivraisonType;
use App\Service\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Paiement\PaiementFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{

    public function __construct(private CartManager $cartManager)
    {
    }
    #[Route('/panier')]
    public function index(): Response
    {
        $produits = $this->cartManager->getCartItems();
        $temp = $this->cartManager->getPanierQuantite();
        if ($temp > 1) {
            $nbProduits = "{$temp} articles";
        } else {
            $nbProduits = "{$temp} article";
        }
        return $this->render('panier/panier.html.twig', ["produits" => $produits, "nbProduits" => $nbProduits]);
    }

    #[Route('/panier/auth')]
    public function auth(Request $request, Security $security): Response
    {
        if(!$this->cartManager->Valide()){
            return $this->redirectToRoute('app_panier_index');
        }
        if ($security->getUser() !== null) {
            return $this->redirectToRoute('app_panier_livraison');
        }

        $form = $this->createForm(InviteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            $email = $form->get('email')->getData();
            $numtel = $form->get('numTel')->getData();
            $request->getSession()->set('nom', $nom);
            $request->getSession()->set('prenom', $prenom);
            $request->getSession()->set('email', $email);
            $request->getSession()->set('numTel', $numtel);
            return $this->redirectToRoute('app_panier_livraison');
        }

        $produits = $this->cartManager->getCartItems();
        $temp = $this->cartManager->getPanierQuantite();
        if ($temp > 1) {
            $nbProduits = "{$temp} articles";
        } else {
            $nbProduits = "{$temp} article";
        }

        return $this->render('panier/authentification.html.twig', ["form" => $form, "produits" => $produits, "nbProduits" => $nbProduits]);
    }

    #[Route('/panier/livraison')]
    public function livraison(Request $request, Security $security): Response
    {
        if(!$this->cartManager->Valide()){
            return $this->redirectToRoute('app_panier_index');
        }
        $a = $this->checkUser($request, $security);
        if ($a !== null){
            return $a;
        };

        /**@var Utilisateur $user */
        $user = $security->getUser();
        $adresse = new EntiteAdresse();

        if($user !== null && !empty($user->getAdresse())){
            $adresse = $user->getAdresse();
        }

        $form = $this->createForm(LivraisonType::class, $adresse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('numeroRue', $form->get('numeroRue')->getData());
            $request->getSession()->set('nomRue', $form->get('nomRue')->getData());
            $request->getSession()->set('ville', $form->get('ville')->getData());
            $request->getSession()->set('region', $form->get('region')->getData());
            $request->getSession()->set('codePost', $form->get('codePost')->getData());
            $request->getSession()->set('pays', $form->get('pays')->getData());
            return $this->redirectToRoute('app_panier_paiementsanstype');
        }

        $produits = $this->cartManager->getCartItems();
        $temp = $this->cartManager->getPanierQuantite();
        if ($temp > 1) {
            $nbProduits = "{$temp} articles";
        } else {
            $nbProduits = "{$temp} article";
        }

        return $this->render('panier/livraison.html.twig', ["form" => $form->createView(), "produits" => $produits, "nbProduits" => $nbProduits]);
    }

    private function checkUser(Request $request, Security $security): ?RedirectResponse
    {
        $session = $request->getSession();
        if ($security->getUser() === null
            &&( $session->get('nom') == null || $session->get('prenom') == null
            || $session->get('email') == null || $session->get('numTel') == null)) {
            return $this->redirectToRoute('app_panier_auth');
        }
        return null;
    }

    #[Route('/panier/paiement/{type}')]
    public function paiement(Request $request, Security $security, TypePayment $type, EntityManagerInterface $manager): Response
    {
        $produits = $this->cartManager->getCartItems();
        $temp = $this->cartManager->getPanierQuantite();
        if ($temp > 1) {
            $nbProduits = "{$temp} articles";
        } else {
            $nbProduits = "{$temp} article";
        }

        if(!$this->cartManager->Valide()){
            return $this->redirectToRoute('app_panier_index');
        }
        $a = $this->checkUser($request, $security);
        if ($a !== null){
            return $a;
        };
        $a = $this->checkLivraison($request, $security);
        if ($a !== null){
            return $a;
        };

        switch ($type) {
            case TypePayment::CB:
                $PaiementMethode = PaiementFactory::getMethodePaiement('CB');
                break;
            case TypePayment::Faux:
                $PaiementMethode = PaiementFactory::getMethodePaiement('Faux');
                break;
            case TypePayment::PAYPAL:
                $PaiementMethode = PaiementFactory::getMethodePaiement('Paypal');
                break;
        }
        $TypeForm = $PaiementMethode->getTypeForm();

        $form = $this->createForm($TypeForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $total = $this->cartManager->prixTotal();

            $produits = $this->cartManager->getCartItems();
            $temp = $this->cartManager->getPanierQuantite();
            if ($temp > 1) {
                $nbProduits = "{$temp} articles";
            } else {
                $nbProduits = "{$temp} article";
            }

            // Cette zone au final devra se trouver sur les different services que l'on appelle Paypal, CB ...
            // Donc les datas seront obtenue en data post
            switch ($type) {
                case TypePayment::CB:
                    $data = ['success' => true, 'total' => $total];
                    break;
                case TypePayment::PAYPAL:
                    $data = ['clef' => 'Valide', 'success' => 'true', 'total' => $total];
                    break;
                case TypePayment::Faux:
                    $data = [];
                    break;
            }
            if ($PaiementMethode->Valider($form, $total, $data)){

                $commande = new EntiteCommande();
                if ($security->getUser() === null){
                    //todo entiter specifique
                    $commande->setNom($request->getSession()->get('nom'));
                    $commande->setPrenom($request->getSession()->get('prenom'));
                    $commande->setEmail($request->getSession()->get('email'));
                    $commande->setNumTel($request->getSession()->get('numTel'));
                }
                $session = $request->getSession();
                $addr = (new EntiteAdresse())
                    ->setNumeroRue($session->get('numeroRue'))
                    ->setNomRue($session->get('nomRue'))
                    ->setVille($session->get('ville'))
                    ->setPays($session->get('pays'))
                    ->setRegion($session->get('region'))
                    ->setCodePost((int)$session->get('codePost'));

                $commande->setAdresse($addr);
                $commande->setPanier($this->cartManager->getPanier());
                $commande->setDateAchat(new \DateTime("now"));
                $commande->setDateArrive((new \DateTime("now"))->add(new \DateInterval("P5D")));
                $commande->setPoidsTotal($this->cartManager->poids());
                $commande->setPrixTotal($this->cartManager->prixTotal());
                $commande->setUtilisateur($security->getUser());

                $manager->persist($addr);
                $manager->persist($commande);
                $manager->flush();
                $this->cartManager->DeAffecter();

                return $this->render('panier/paiement/payer.html.twig', ['id' => $commande->getId(), "produits" => $produits, "nbProduits" => $nbProduits]);
            }
        }


        return $this->render('panier/paiement/paiement.html.twig', ['form' => $form, "produits" => $produits, "nbProduits" => $nbProduits]);
    }

    private function checkLivraison(Request $request, Security $security): ?RedirectResponse
    {
        $session = $request->getSession();
        if ($session->get('numeroRue') == null && $session->get('nomRue') == null
            && $session->get('ville') == null && $session->get('region') == null
            && $session->get('codePost') == null && $session->get('pays') == null) {
            return $this->redirectToRoute('app_panier_livraison');
        }
        return null;
    }

    #[Route('/panier/paiement')]
    public function paiementSansType(): Response
    {
        return $this->redirectToRoute('app_panier_paiement', ['type' => TypePayment::PAYPAL->value]);
    }
}

enum TypePayment: string
{
    case PAYPAL = "Paypal";
    case CB = "CB";
    case Faux = "Faux";
}

