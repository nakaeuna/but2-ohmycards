<?php

namespace App\Controller\Admin;

use App\Entity\EntiteCommande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class CommandeCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntiteCommande::class;
    }

    public function configureCrud(Crud $crud): Crud {
        $crud->setPageTitle(Crud::PAGE_INDEX, "Liste des Commandes");
        return $crud;
    }


    public function configureFields(string $pageName): iterable
    {
        yield FormField::addColumn(3);
        yield IdField::new('id', 'ID')->onlyOnIndex();
        yield TextField::new('nom', 'Nom')->onlyOnIndex();
        yield TextField::new('prenom', 'Prénom')->onlyOnIndex();
        yield TextField::new('email', 'Email');
        yield TextField::new('numTel', 'num tel');
        yield DateTimeField::new('dateAchat', 'Date achat');
        yield DateTimeField::new('dateArrive', 'Date arrive');
        yield NumberField::new('poidsTotal', 'Poids');
        yield NumberField::new('prixTotal', 'Prix');
        yield FormField::addColumn(9);
        yield AssociationField::new('panier', 'Panier')->hideOnIndex()->renderAsEmbeddedForm();
        yield AssociationField::new('adresse', 'Adresse')->onlyOnForms()->renderAsEmbeddedForm();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ;
    }

}
