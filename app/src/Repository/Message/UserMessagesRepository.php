<?php

namespace App\Repository\Message;

use App\Entity\Customer;
use App\Entity\Message\UserMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMessages[]    findAll()
 * @method UserMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMessages::class);
    }

    public function findByCustomer(Customer $customer)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.customer', 'c')
            ->orWhere('u.sender = :sender')
            ->orWhere('c.id = :id')
            ->setParameter('sender', $customer)
            ->setParameter('id', $customer->getId())
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?UserMessages
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
