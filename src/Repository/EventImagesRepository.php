<?php

namespace App\Repository;

use App\Entity\EventImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventImages[]    findAll()
 * @method EventImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventImages::class);
    }

    // /**
    //  * @return EventImages[] Returns an array of EventImages objects
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
    public function findOneBySomeField($value): ?EventImages
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
