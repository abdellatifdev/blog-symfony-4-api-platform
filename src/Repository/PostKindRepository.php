<?php

namespace App\Repository;

use App\Entity\PostKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostKind|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostKind|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostKind[]    findAll()
 * @method PostKind[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostKindRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostKind::class);
    }

    // /**
    //  * @return PostKind[] Returns an array of PostKind objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostKind
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
