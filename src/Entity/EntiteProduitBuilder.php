<?php

use App\Entity\EntiteImageProduit;
use App\Entity\EntiteJeuxDeCartes;
use App\Entity\EntiteJeuxDeSociete;
use App\Entity\EntiteProduit;
use App\Entity\EntitePromo;
use App\Entity\EntiteTCG;
use App\Form\nbCarte2;
use App\form\theme;
use App\Entity\TypeTCG;
use Doctrine\Persistence\ObjectManager;

abstract class EntiteProduitBuilder
{
    protected EntiteProduit $entiteProduit;

    public function __construct()
    {
        $this->entiteProduit = $this->createEntiteProduit();
    }

    abstract protected function createEntiteProduit(): EntiteProduit;

    public function setNom(string $nom): self
    {
        $this->entiteProduit->setNom($nom);
        return $this;
    }

    public function setDescription(string $description): EntiteProduitBuilder
    {
        $this->entiteProduit->setDescription($description);
        return $this;
    }

    public function setImage(string $image): EntiteProduitBuilder
    {
        $this->entiteProduit->setImage($image);
        return $this;
    }

    public function addImage(EntiteImageProduit $image): EntiteProduitBuilder
    {
        $this->entiteProduit->addImage($image);
        return $this;
    }

    public function setPrixBaseHT(int $prixBaseHT): EntiteProduitBuilder
    {
        $this->entiteProduit->setPrixTTC($prixBaseHT);
        return $this;
    }

    public function setQuantite(int $quantite): EntiteProduitBuilder
    {
        $this->entiteProduit->setQuantite($quantite);
        return $this;
    }

    public function setPromo(EntitePromo $promo): EntiteProduitBuilder
    {
        $this->entiteProduit->setPromo($promo);
        return $this;
    }

    public function setNbVente(int $nbVente): EntiteProduitBuilder
    {
        $this->entiteProduit->setNbVente($nbVente);
        return $this;
    }

    public function setPoids(int $poids): EntiteProduitBuilder
    {
        $this->entiteProduit->setPoids($poids);
        return $this;
    }

    public function setEnVente(bool $enVente): EntiteProduitBuilder
    {
        $this->entiteProduit->setEnVente($enVente);
        return $this;
    }

    public function setTheme(theme $theme): EntiteProduitBuilder
    {
        $this->entiteProduit->setTheme($theme);
        return $this;
    }

    public function build(?ObjectManager $manager = null): EntiteProduit
    {
        if ($manager != null) {
            $manager->persist($this->entiteProduit);
        }

        return $this->entiteProduit;
    }
}

class EntiteJeuxDeCartesBuilder extends EntiteProduitBuilder
{
    public function setNbCartes(nbCarte2 $nbCartes): EntiteProduitBuilder
    {
        $this->entiteProduit->setNbCartes($nbCartes);
        return $this;
    }


    protected function createEntiteProduit(): EntiteProduit
    {
        return new EntiteJeuxDeCartes();
    }
}

class EntiteTCGBuilder extends EntiteProduitBuilder
{
    public function setGeneration(string $generation): EntiteProduitBuilder
    {
        $this->entiteProduit->setGeneration($generation);
        return $this;
    }

    public function setTypeTCG(TypeTCG $typeTCG): static
    {
        $this->entiteProduit->setTypeTCG($typeTCG);
        return $this;
    }

    protected function createEntiteProduit(): EntiteProduit
    {
        return new EntiteTCG();
    }
}

class EntiteJeuxDeSocieteBuilder extends EntiteProduitBuilder
{
    public function setAgeRecommander(int $ageRecommader): EntiteProduitBuilder
    {
        $this->entiteProduit->setAgeRecommander($ageRecommader);
        return $this;
    }


    protected function createEntiteProduit(): EntiteProduit
    {
        return new EntiteJeuxDeSociete();
    }
}