<?php

namespace App\Repository;

use App\Entity\ErrorDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ErrorDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ErrorDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ErrorDetail[]    findAll()
 * @method ErrorDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ErrorDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ErrorDetail::class);
    }

    // /**
    //  * @return ErrorDetail[] Returns an array of ErrorDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ErrorDetail
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
