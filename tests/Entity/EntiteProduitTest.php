<?php

namespace App\Tests\Entity;

use App\Entity\EntiteJeuxDeCartes;
use App\Entity\EntiteJeuxDeSociete;
use App\Entity\EntiteProduit;
use App\Entity\EntiteTCG;
use App\Form\theme;
use PHPUnit\Framework\TestCase;

class EntiteProduitTest extends TestCase
{

    public function test__toString()
    {
        $produit1 = (new EntiteJeuxDeCartes())->setNom("produit1");
        $produit2 = (new EntiteJeuxDeCartes())->setNom("produit2");
        $produit3 = (new EntiteTCG())->setNom("produit3");

        $this->assertSame($produit1->getNom(), (string)$produit1);
        $this->assertSame($produit2->getNom(), (string)$produit2);
        $this->assertSame($produit3->getNom(), (string)$produit3);
    }

    public function testBuilder()
    {
        $produit = EntiteJeuxDeCartes::builder()
            ->setNom('nom')
            ->setDescription('description')
            ->setPrixBaseHT('prixBaseHT')
            ->setQuantite('quantite')
            ->setNbVente(rand(0, 100))
            ->setPoids('poids')
            ->setEnVente('enVente')
            ->setTheme(theme::YU_GI_OH)
            ->build();

        $this->assertSame('nom' ,$produit->getNom());
    }
}
