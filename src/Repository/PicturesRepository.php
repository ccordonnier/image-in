<?php

namespace App\Repository;

use App\Entity\Pictures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pictures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pictures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pictures[]    findAll()
 * @method Pictures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PicturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pictures::class);
    }

    // /**
    //  * @return Pictures[] Returns an array of Pictures objects
    //  */
    
    public function findByField($field,$value,$maxvalue=10)
    {
        return $this->createQueryBuilder('pictures')
            ->andWhere('pictures.'.$field.' = :val')
            ->setParameter('val', $value)
            ->orderBy('pictures.id', 'ASC')
            ->setMaxResults($maxvalue)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Pictures
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
