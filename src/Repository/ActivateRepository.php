<?php

namespace App\Repository;

use App\Entity\Activate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activate[]    findAll()
 * @method Activate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activate::class);
    }

    // /**
    //  * @return Activate[] Returns an array of Activate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneBySomeField($token): ?Activate
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.token = :val')
            ->setParameter('val', $token)
            ->getQuery()
            ->getResult()
        ;
    }

}
