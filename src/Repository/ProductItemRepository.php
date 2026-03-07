<?php

namespace App\Repository;

use App\Entity\ProductItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\PromotionLoyalty;

class ProductItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductItem::class);
    }

   public function findLoyaltyByProductId(int $productId): ?PromotionLoyalty
   {
    $promo = $this->getEntityManager()
        ->createQueryBuilder()
        ->select('promo') 
        ->from(\App\Entity\Promotion::class, 'promo')
        ->join('promo.productItems', 'pi')
        ->join('pi.product', 'p')
        ->where('p.id = :id')
        ->setParameter('id', $productId)
        ->getQuery()
        ->getOneOrNullResult();

    return $promo?->getPromotionLoyalty();
    }

}