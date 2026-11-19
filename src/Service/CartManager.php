<?php
// src/Service/CartManager.php

namespace App\Service;


use App\Entity\EntiteProduit;
use App\Entity\Panier;
use App\Entity\ProduitsDansLePanier;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class CartManager
{
    private $requestStack;
    private ?Panier $panier = null;
    public function __construct(RequestStack $requestStack, private EntityManagerInterface $entityManager, private Security $security)
    {
        $this->requestStack = $requestStack;
    }

    private function getCartId(): string
    {
        $user = $this->security->getUser(); //todo probleme session vs utilisateur a réglé

        if ($user == null) {
            $cartId = $this->requestStack->getSession()->get('cart_id');

            if (!$cartId) {
                $cartId = uniqid();
                $this->requestStack->getSession()->set('cart_id', $cartId);
            }
            return $cartId;
        }

        return $user->getUserIdentifier();
    }

    public function add( int $id_produit, int $quantite)
    {
        $panier = $this->getPanier();

        $produit_panier = $this->entityManager->getRepository(ProduitsDansLePanier::class)
            ->createQueryBuilder("p")
            ->where("p.panier = :panier")
            ->andwhere("p.produit = :id")
            ->setParameter("panier", $panier->getId())
            ->setParameter("id", $id_produit)
            ->getQuery()->execute();

        if (count($produit_panier) == 0) {
            $produit = $this->entityManager->getRepository(EntiteProduit::class)->find($id_produit);
            $produit_panier = (new ProduitsDansLePanier())->setProduit($produit)->setPanier($panier)->setQuantite($quantite);
            $this->entityManager->persist($produit_panier);
            $panier->addProduit($produit_panier);
        } else {
            $produit_panier = $produit_panier[0];
            $produit_panier->setQuantite($produit_panier->getQuantite() + $quantite);
        }
        $this->entityManager->flush();
    }

    public function plus( int $id_produit, int $quantite)
    {
        $panier = $this->getPanier();

        $produit_panier = $this->entityManager->getRepository(ProduitsDansLePanier::class)
            ->createQueryBuilder("p")
            ->where("p.panier = :panier")
            ->andwhere("p.produit = :id")
            ->setParameter("panier", $panier->getId())
            ->setParameter("id", $id_produit)
            ->getQuery()->execute();


        if (count($produit_panier) == 0) {
            $produit = $this->entityManager->getRepository(EntiteProduit::class)->find($id_produit);
            $produit_panier = (new ProduitsDansLePanier())->setProduit($produit)->setPanier($panier)->setQuantite($quantite);
            $this->entityManager->persist($produit_panier);
            $panier->addProduit($produit_panier);
        } else {
            $quantite = $produit_panier[0]->getQuantite() + $quantite;

            if ($quantite <= 0){
                $this->delete($id_produit);
                return;
            }

            $produit_panier = $produit_panier[0];
            $produit_panier->setQuantite($quantite);
        }
        $this->entityManager->flush();
    }

    public function delete(int $id_produit)
    {
        $panier = $this->getPanier();

        $produit_panier = $this->entityManager->getRepository(ProduitsDansLePanier::class)
            ->createQueryBuilder("p")
            ->where("p.panier = :panier")
            ->andwhere("p.produit = :id")
            ->setParameter("panier", $panier->getId())
            ->setParameter("id", $id_produit)
            ->getQuery()->execute();

        if (count($produit_panier) == 0) {
            return;
        }
        $tailleAvant = count($produit_panier);
        $produit = $produit_panier[0];
        $panier->removeProduit($produit);
        if (count($produit_panier)>=$tailleAvant){
         throw new Error("Le produit n'a pas été supprimé");
        }
        $this->entityManager->flush();
    }

    public function getCartItems(): \Iterator {
        $panier = $this->getPanier();
        return $panier->getIterator();
    }

    public function Valide(): bool {
        $panier = $this->getPanier();
        if (iterator_count($panier) == 0)
            return false;

//        // enlève les produits qui ont plus de quantite demander que de disponible
//        foreach ($panier as $produit) {
//            if ($produit->getQuantite() > $produit->getProduit()->getQuantite()) {
//                $panier->removeProduit($produit);
//                $this->entityManager->flush();
//                return false;
//            }
//        }

        return true;
    }

    public function prixTotal(): int {
        $panier = $this->getPanier();
        $total = 0;
        foreach ($panier->getIterator() as $Produit){
            $total += $Produit->getQuantite() * $Produit->getProduit()->getPrix();
        }
        return $total;
    }

    public function poids(): int {
        $panier = $this->getPanier();
        $total = 0;
        foreach ($panier->getIterator() as $Produit){
            $total += $Produit->getQuantite() * $Produit->getProduit()->getPoids();
        }
        return $total;
    }

    public function DeAffecter()
    {
        $panier = $this->getPanier();

        //foreach ($panier as $produit){
        //    $produit->getproduit()->setQuantite($produit->getproduit()->getQuantite() - $produit->getQuantite());
        //    $produit->getproduit()->setnbVente($produit->getproduit()->getnbVente() + $produit->getQuantite());
        //}

        $panier->setIdSession(null);
        $this->entityManager->flush();
    }


    public function getPanier(): Panier
    {
        if ($this->panier != null){
            return $this->panier;
        }
        $cartId = $this->getCartId();
        $panier = $this->entityManager->getRepository(Panier::class)
            ->createQueryBuilder("c")
            ->where("c.id_Session = :id")
            ->setParameter("id", $cartId)
            ->getQuery()->execute();

        if (count($panier) == 0) {
            $panier = (new Panier())
                ->setIdSession($cartId);
            $this->entityManager->persist($panier);
        } else{
            $panier = $panier[0];
        }

        $this->panier = $panier;
        return $panier;
    }

    public function getPanierQuantite(): Int
    {
        $panier = $this->getPanier();
        $quantite = 0;

        foreach ($panier as $produit){
            $quantite += 1;
        }

        return $quantite;
    }
}
