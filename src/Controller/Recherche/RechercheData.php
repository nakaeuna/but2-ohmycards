<?php
namespace App\Controller\Recherche;

use App\Form\categorie;
use App\Form\theme;

class RechercheData
{
    public int $page = 1;

    public ?string $nom = '';

    public ?int $prixMax;

    public ?int $prixMin;
    public bool $promo = false; //todo ajouter au form

    public theme $theme = theme::TOUS;

    public categorie $categorie = categorie::produit;

}