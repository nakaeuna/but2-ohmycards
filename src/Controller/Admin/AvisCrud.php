<?php

namespace App\Controller\Admin;

use App\Entity\EntiteAvis;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AvisCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntiteAvis::class;
    }

    public function configureCrud(Crud $crud): Crud {
        $crud->setPageTitle(Crud::PAGE_INDEX, "Liste des Avis");
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) { return $action->setLabel('Ajouter un Avis'); });
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('produit', 'Produit'),
            AssociationField::new('utilisateur', 'Utilisateur'),
            NumberField::new('note', 'Note'),
            TextareaField::new('avis', 'Avis')->onlyOnForms(),
        ];
    }

}
