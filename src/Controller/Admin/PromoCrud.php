<?php

namespace App\Controller\Admin;

use App\Entity\EntiteProduit;
use App\Entity\EntitePromo;
use App\Repository\EntiteProduitRepository;
use App\Repository\EntitePromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PromoCrud extends AbstractCrudController
{

    function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public static function getEntityFqcn(): string
    {
        return EntitePromo::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('dateDebut'),
            DateField::new('dateFin'),
            PercentField::new('pourcentageReduction')->setStoredAsFractional(false),
            AssociationField::new('produits')->onlyWhenCreating()->setFormTypeOption('by_reference', false)
                ->setQueryBuilder(
                    fn (QueryBuilder $queryBuilder) =>
                    $queryBuilder
                        ->select('produit')
                        ->from(EntiteProduit::class, 'produit')
                        ->where('produit.promo is null')
                        ->orderBy('produit.nom')
            ),
            AssociationField::new('produits')->onlyWhenUpdating()->setFormTypeOption('by_reference', false)
        ];
    }
}
