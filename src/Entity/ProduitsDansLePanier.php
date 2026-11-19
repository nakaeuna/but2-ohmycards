<?php

namespace App\Entity;

use App\Repository\ProduitsDansLePanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Positive;

#[ORM\Entity(repositoryClass: ProduitsDansLePanierRepository::class)]
class ProduitsDansLePanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?EntiteProduit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Panier $panier = null;

    #[ORM\Column, Positive]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?EntiteProduit
    {
        return $this->produit;
    }

    public function setProduit(?EntiteProduit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): static
    {
        $this->panier = $panier;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function prix(): int
    {
        return $this->quantite * $this->produit->getPrix();
    }

    public function __toString(): string
    {
        return $this->quantite . ' | ' . $this->produit->getNom();
    }
}
