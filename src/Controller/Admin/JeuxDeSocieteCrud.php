<?php

namespace App\Controller\Admin;

use App\Entity\EntiteJeuxDeSociete;
use App\Form\ImageProduitType;
use App\Form\theme;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JeuxDeSocieteCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntiteJeuxDeSociete::class;
    }

    public function configureCrud(Crud $crud): Crud {
        $crud->setPageTitle(Crud::PAGE_INDEX, "Liste des jeux de société");
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) { return $action->setLabel('Ajouter un jeux de société'); });
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
            IntegerField::new('ageRecommander'),
            ChoiceField::new('theme')->setChoices(theme::cases())->hideOnIndex(),
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
