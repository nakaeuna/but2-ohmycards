<?php

namespace App\Entity;

use App\Form\nbCarte2;
use App\Form\theme;
use App\Repository\EntiteProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use EntiteProduitBuilder;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

#[ORM\Entity(repositoryClass: EntiteProduitRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
//#[DiscriminatorMap(['person' => Person::class, 'employee' => Employee::class])]
abstract class EntiteProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, EntiteImageProduit>
     */
    #[ORM\OneToMany(targetEntity: EntiteImageProduit::class,
        mappedBy: 'entiteProduit', cascade: ['persist'], orphanRemoval: true)]
    private Collection $images;

    #[ORM\Column, Positive]
    private ?int $prixTTC = null;

    #[ORM\Column, PositiveOrZero]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?EntitePromo $promo = null;

    #[ORM\Column, PositiveOrZero]
    private ?int $nbVente = null;

    /**
     * @var Collection<int, EntiteAvis>
     */
    #[ORM\OneToMany(targetEntity: EntiteAvis::class, mappedBy: 'produit', orphanRemoval: true)]
    private Collection $avis;

    #[ORM\Column(options: ["default" => 5]), Positive]
    private ?int $poids = null;

    #[ORM\Column(length: 255, options: ["default" => theme::POKEMON])]
    private ?theme $theme = null;

    #[ORM\Column(options: ["default" => true])]
    private ?bool $enVente = null;

    #[ORM\Column(nullable: true, options: ["default" => 0]), PositiveOrZero, LessThanOrEqual(5)]
    private ?float $MoyenneNote = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, EntiteImageProduit>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(EntiteImageProduit $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setEntiteProduit($this);
        }

        return $this;
    }

    public function removeImage(EntiteImageProduit $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getEntiteProduit() === $this) {
                $image->setEntiteProduit(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?int
    {
        //todo calcule réduction
        if ($this->promo != null) {
            return $this->prixTTC * (1 - $this->promo->getPourcentageReduction()/100);
        }

        return $this->prixTTC;
    }

    public function getPromoValide(): ?EntitePromo {

        $test = $this->promos->getValues();
        foreach ($test as $promo){
            if ($promo->getDateDebut() > time() && $promo->getDateFin() < time()){
                return $promo;
            }
        }
        return null;
    }

    public function getPrixTTC(): ?int
    {
        return $this->prixTTC;
    }

    public function setPrixTTC(int $prixTTC): static
    {
        $this->prixTTC = $prixTTC;

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

    public function __toString(): String {
        return $this->getNom();
    }

    public function getPromo(): ?EntitePromo
    {
        return $this->promo;
    }

    public function setPromo(?EntitePromo $promo): static
    {
        $this->promo = $promo;

        return $this;
    }

    public function getNbVente(): ?int
    {
        return $this->nbVente;
    }

    public function setNbVente(int $nbVente): static
    {
        $this->nbVente = $nbVente;

        return $this;
    }

    /**
     * @return Collection<int, EntiteAvis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    //public function moyenneAvis(): int
    //{
    //    $total = 0;
    //    foreach ($this->avis as $avis){
    //        $total += $avis->getNote();
    //    }
//
    //    $count = count($this->avis);
    //    if ($count > 0){
    //        return $total/$count;
    //    }
    //    return 0;
    //}

    public function addAvi(EntiteAvis $avis): static
    {
        if (!$this->avis->contains($avis)) {
            $this->avis->add($avis);
            $avis->setProduit($this);
        }

        return $this;
    }

    public function removeAvi(EntiteAvis $avis): static
    {
        if ($this->avis->removeElement($avis)) {
            // set the owning side to null (unless already changed)
            if ($avis->getProduit() === $this) {
                $avis->setProduit(null);
            }
        }

        return $this;
    }

    public abstract static function builder(): EntiteProduitBuilder;

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTheme(): ?theme
    {
        return $this->theme;
    }

    public function setTheme(theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function isEnVente(): ?bool
    {
        return $this->enVente;
    }

    public function setEnVente(bool $enVente): static
    {
        $this->enVente = $enVente;

        return $this;
    }

    public function getMoyenneNote(): ?float
    {
        return $this->MoyenneNote;
    }

    public function setMoyenneNote(float $MoyenneNote): static
    {
        $this->MoyenneNote = $MoyenneNote;

        return $this;
    }
}


#[ORM\Entity]
class EntiteJeuxDeCartes extends EntiteProduit{
    #[ORM\Column]
    public ?nbCarte2 $nbCartes = null;

    public function getNbCartes(): ?nbCarte2
    {
        return $this->nbCartes;
    }

    public function setNbCartes(nbCarte2 $nbCartes): static
    {
        $this->nbCartes = $nbCartes;

        return $this;
    }

    public static function builder(): EntiteProduitBuilder
    {
        return new \EntiteJeuxDeCartesBuilder();
    }
}


enum TypeTCG : string
{
    case BOOSTER = "booster";
    case DISPLAY  = "display";
    case PACK = "pack";
    case CARTE = "carte";
}

#[ORM\Entity]
class EntiteTCG extends EntiteProduit{
    #[ORM\Column(length: 255)]
    public ?string $generation = null;

    #[ORM\Column(length: 255)]
    public ?TypeTCG $typeTCG = null;

    public function getGeneration(): ?string
    {
        return $this->generation;
    }

    public function setGeneration(string $generation): static
    {
        $this->generation = $generation;

        return $this;
    }

    public function getTypeTCG(): ?TypeTCG
    {
        return $this->typeTCG;
    }

    public function setTypeTCG(TypeTCG $typeTCG): static
    {
        $this->typeTCG = $typeTCG;

        return $this;
    }

    public static function builder(): EntiteProduitBuilder
    {
        return new \EntiteTCGBuilder;
    }
}

#[ORM\Entity]
class EntiteJeuxDeSociete extends EntiteProduit{
    #[ORM\Column, Positive]
    public ?int $ageRecommander = null;
    public function getAgeRecommander(): ?int
    {
        return $this->ageRecommander;
    }

    public function setAgeRecommander(int $ageRecommander): static
    {
        $this->ageRecommander = $ageRecommander;

        return $this;
    }

    public static function builder(): EntiteProduitBuilder
    {
        return new \EntiteJeuxDeSocieteBuilder();
    }
}
