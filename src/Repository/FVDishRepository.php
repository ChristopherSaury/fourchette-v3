<?php

namespace App\Repository;

use App\Entity\FVDish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FVDish>
 */
class FVDishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FVDish::class);
    }

    public function findDishes(int $value, int $max_result){
        return $this->createQueryBuilder('d')
        ->andWhere('d.category = :val')
        ->setParameter('val', $value)
        ->setMaxResults($max_result)
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return FVDish[] Returns an array of FVDish objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FVDish
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
