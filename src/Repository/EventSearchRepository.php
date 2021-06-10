<?php

namespace App\Repository;

use App\Entity\EventSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventSearch[]    findAll()
 * @method EventSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventSearch::class);
    }

    // /**
    //  * @return EventSearch[] Returns an array of EventSearch objects
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
    public function findOneBySomeField($value): ?EventSearch
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
