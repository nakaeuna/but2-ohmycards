<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Iterator;
use Traversable;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier implements \IteratorAggregate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, ProduitsDansLePanier>
     */
    #[ORM\OneToMany(targetEntity: ProduitsDansLePanier::class, mappedBy: 'panier', orphanRemoval: true)]
    private Collection $produits;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $id_Session = null;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ProduitsDansLePanier>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(ProduitsDansLePanier $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setPanier($this);
        }

        return $this;
    }

    public function removeProduit(ProduitsDansLePanier $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getPanier() === $this) {
                $produit->setPanier(null);
            }
        }

        return $this;
    }

    public function getIdSession(): ?string
    {
        return $this->id_Session;
    }

    public function setIdSession(?string $id_Session): static
    {
        $this->id_Session = $id_Session;

        return $this;
    }

    public function getIterator(): Iterator
    {
        return new IterateurAlphabetique($this);
    }


    public function getReverseIterator(): Iterator
    {
        return new IterateurAlphabetique($this, true);
    }
}

class IterateurAlphabetique implements \Iterator
{
    private $position = 0;

    public function __construct(private $produits)
    {
    }

    public function rewind() :void
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->produits->getProduits()[$this->position];
    }

    public function key(): bool
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position = $this->position + 1;
    }

    public function valid(): bool
    {
        return isset($this->produits->getProduits()[$this->position]);
    }
}
