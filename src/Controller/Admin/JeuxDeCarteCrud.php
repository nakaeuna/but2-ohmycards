<?php

namespace App\Controller\Admin;

use App\Entity\EntiteTCG;
use App\Entity\EntiteJeuxDeCartes;
use App\Entity\nbCarte;
use App\Form\ImageProduitType;
use App\Form\nbCarte2;
use App\Form\theme;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/jeux-de-carte', name: 'admin')]
class JeuxDeCarteCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntiteJeuxDeCartes::class;
    }

    public function configureCrud(Crud $crud): Crud {
        $crud->setPageTitle(Crud::PAGE_INDEX, "Liste des jeux de cartes");
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) { return $action->setLabel('Ajouter un jeux de carte'); });
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(3),
            IdField::new('nom'),
            MoneyField::new('prixTTC')->setCurrency('EUR')->setLabel("Prix TTC"),
            MoneyField::new('Prix')->setCurrency('EUR')->hideOnForm(),
            IntegerField::new('quantite'),
            IntegerField::new('poids')->hideOnIndex(),
            IntegerField::new('nbVente')->hideWhenCreating(),
            ChoiceField::new('theme')->setChoices(theme::cases()),
            ChoiceField::new('nbCartes')->setChoices(nbCarte2::cases()),
            BooleanField::new('enVente'),
            FormField::addColumn(9),
            TextEditorField::new('description')->hideOnIndex(),
            CollectionField::new('images')->setEntryType(ImageProduitType::class)->hideOnIndex(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $entity = parent::createEntity($entityFqcn);
        $entity->setNbVente(0);
        return $entity;
    }
}

