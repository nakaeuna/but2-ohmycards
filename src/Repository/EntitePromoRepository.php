<?php

namespace App\Repository;

use App\Entity\EntiteProduit;
use App\Entity\EntitePromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EntitePromo>
 */
class EntitePromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntitePromo::class);
    }

    public function preRemove(EntitePromo $promo, LifecycleEventArgs $args)
    {
        $entityManager = $args->getObjectManager();
        $produits = $entityManager->getRepository(EntiteProduit::class)->findBy(['promo' => $promo]);

        foreach ($produits as $produit) {
            $produit->setPromo(null); // or set it to another promo entity
            $entityManager->persist($produit);
        }
        $entityManager->flush();
    }

    //    /**
    //     * @return EntitePromo[] Returns an array of EntitePromo objects
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

    //    public function findOneBySomeField($value): ?EntitePromo
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
