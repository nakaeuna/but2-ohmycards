<?php

namespace App\Entity;

use App\Repository\EntitePromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

#[ORM\Entity(repositoryClass: EntitePromoRepository::class)]
class EntitePromo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column, PositiveOrZero, LessThanOrEqual(100)]
    private ?int $pourcentageReduction = null;

    /**
     * @var Collection<int, EntiteProduit>
     */
    #[ORM\OneToMany(targetEntity: EntiteProduit::class, mappedBy: 'promo', cascade: ['persist'])]
    private Collection $produits;


    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPourcentageReduction(): ?int
    {
        return $this->pourcentageReduction;
    }

    public function setPourcentageReduction(int $pourcentageReduction): static
    {
        $this->pourcentageReduction = $pourcentageReduction;

        return $this;
    }

    /**
     * @return Collection<int, EntiteProduit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(EntiteProduit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setPromo($this);
        }

        return $this;
    }

    public function removeProduit(EntiteProduit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getPromo() === $this) {
                $produit->setPromo(null);
            }
        }

        return $this;
    }
}
