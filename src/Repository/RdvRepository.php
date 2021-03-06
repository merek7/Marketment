<?php

namespace App\Repository;

use App\Entity\Rdv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rdv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rdv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rdv[]    findAll()
 * @method Rdv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rdv::class);
    }
    public function Countrdv()
    {
        return $this->createQueryBuilder('e')
            ->select('(COUNT(e.id))')
            ->getQuery()
            ->getSingleResult();
    }
    public function findAllbydate()
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByidentifier(Int $id)
    {
        return $this->createQueryBuilder('e')
            ->leftjoin('e.entreprises', 'r')
            ->where('r.users =:val')
            ->setParameter('val', $id)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Rdv[] Returns an array of Rdv objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rdv
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
