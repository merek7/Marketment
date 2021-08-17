<?php

namespace App\Repository;

use App\Entity\Entreprise;
use App\Entity\Prospect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prospect|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prospect|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prospect[]    findAll()
 * @method Prospect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProspectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prospect::class);
    }
    public function findAllbydate()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByidentifier(Int $id)
    {
        return $this->createQueryBuilder('e')
            ->join('e.entreprises', 'r')
            ->where('r.users =:val')
            ->setParameter('val', $id)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function CountProspect()
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function CountMyProspect($user)
    {
        return $this->createQueryBuilder('e')
            ->join('e.entreprises', 'r')
            ->select('COUNT(e.id)')
            ->where('r.users =:val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function RdvEntreprise()
    {
        $query = $this->createQueryBuilder('e')
            ->join('e.entreprises', 'r')
            ->select('r.Contact');

        return $query;
    }
    public function ProspectByUser($user)
    {
        $query = $this->createQueryBuilder('e')
            ->join('e.entreprises', 'r')
            ->select('e')
            ->where('r.users =:val')
            ->setParameter('val', $user)
            ->addOrderBy('e.date', 'DESC');
        return $query;
    }
    public function ProspectByUserQuery($user)
    {
        $query = $this->createQueryBuilder('e')
            ->join('e.entreprises', 'r')
            ->select('e')
            ->where('r.users =:val')
            ->setParameter('val', $user)
            ->addOrderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
        return $query;
    }
    // /**
    //  * @return Prospect[] Returns an array of Prospect objects
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
    public function findOneBySomeField($value): ?Prospect
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
