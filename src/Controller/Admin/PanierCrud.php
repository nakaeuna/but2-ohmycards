<?php

namespace App\Controller\Admin;

use App\Entity\EntiteProduit;
use App\Entity\Panier;
use App\Form\PanierType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PanierCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Panier::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield CollectionField::new('produits', 'Produits')->setEntryType(PanierType::class);
    }
}
