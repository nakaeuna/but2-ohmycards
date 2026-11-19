<?php

namespace App\EventListener;

use App\Entity\EntitePromo;
use App\Entity\EntiteProduit;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: EntitePromo::class)]
class EntitePromoListener
{
    public function preRemove(EntitePromo $promo, PreRemoveEventArgs $args)
    {
        $entityManager = $args->getObjectManager();
        $produits = $entityManager->getRepository(EntiteProduit::class)->findBy(['promo' => $promo]);

        foreach ($produits as $produit) {
            $produit->setPromo(null);
            $entityManager->persist($produit);
        }
    }
}