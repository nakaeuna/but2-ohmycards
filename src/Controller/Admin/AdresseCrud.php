<?php

namespace App\Controller\Admin;

use App\Entity\EntiteAdresse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdresseCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntiteAdresse::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nomRue', 'Nom Rue'),
            TextField::new('NumeroRue', 'Numero Rue'),
            NumberField::new('codePost', 'Code Postal'),
            TextField::new('ville', 'Ville'),
            TextField::new('region', 'Region'),
            TextField::new('pays', 'Pays'),
        ];
    }

}
