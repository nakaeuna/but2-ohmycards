<?php

namespace App\Repository;

use App\Controller\Recherche\RechercheData;
use App\Entity\EntiteProduit;
use App\Form\categorie;
use App\Form\theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<EntiteProduit>
 */
class EntiteProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, EntiteProduit::class);
    }

    //    /**
    //     * @return EntiteProduit[] Returns an array of EntiteProduit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EntiteProduit
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findSearch(RechercheData $recherche): PaginationInterface
    {
        $qb = $this->createQueryBuilder("p")
            ->addSelect('promo')
            ->addSelect('CASE WHEN promo.pourcentageReduction IS NOT NULL THEN p.prixTTC * (1.0 - promo.pourcentageReduction / 100.0) ELSE p.prixTTC END AS prixFinal')
            ->leftJoin('p.promo', 'promo')
            ->groupBy('p.id, promo.pourcentageReduction, promo.id')
            ->leftJoin('p.avis', 'avis')
            ->andWhere("p.enVente = TRUE");

        if (!empty($recherche->nom)){
           $qb
               ->andWhere("p.nom LIKE :nom")
               ->setParameter("nom", '%'.$recherche->nom.'%');
        }

        if (!empty($recherche->prixMin)){
            $qb->andwhere("p.prixTTC >= :prixMin")
                ->setParameter("prixMin", $recherche->prixMin);
        }

        if (!empty($recherche->prixMax)){
            $qb->andwhere("p.prixTTC <= :prixMax")
            ->setParameter("prixMax", $recherche->prixMax);
        }

        if (!empty($recherche->theme)){
            if ($recherche->theme != theme::TOUS) {
                $qb->andWhere('p.theme like :theme')
                    ->setParameter('theme', $recherche->theme);
            }
        }

        if (!empty($recherche->categorie)){
            switch ($recherche->categorie) {
                case categorie::produit:
                    break;
                case categorie::jeuxDeCarte:
                    $qb->andWhere('p INSTANCE OF App\Entity\EntiteJeuxDeCartes');
                    break;
                case categorie::TCG:
                    $qb->andWhere('p INSTANCE OF App\Entity\EntiteTCG');
                    break;
                case categorie::jeuxDeSociete:
                    $qb->andWhere('p INSTANCE OF App\Entity\EntiteJeuxDeSociete');
                    break;
            }
        }

        $query = $qb->getQuery();

        return $this->paginator->paginate(
            $query,
            $recherche->page,
            15
        );
    }
}
