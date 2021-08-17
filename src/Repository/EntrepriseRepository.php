<?php

namespace App\Repository;

use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

/**
 * @method Entreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprise[]    findAll()
 * @method Entreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepriseRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entreprise::class);
    }
    public function findAll()
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.dateajout', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function valider($nom)
    {
        $valider = true;
        $query = $this->createQueryBuilder('e')
            ->join('e.users', 'r')
            ->Where('r.id =:id')
            ->andWhere('e.Etat =:valider')
            ->setParameters(
                [
                    'valider' => $valider,
                    'id' => $nom
                ]
            )
            ->orderBy('e.dateajout', 'DESC');
        return $query;
    }
    public function findByidentifier(Int $id)
    {
        return $this->createQueryBuilder('e')
            ->join('e.users', 'r')
            ->where('r.id =:val')
            ->setParameter('val', $id)
            ->orderBy('e.dateajout', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function CountEntreprise()
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function CountMyEntreprise($user)
    {
        return $this->createQueryBuilder('e')
            ->join('e.users', 'r')
            ->select('COUNT(e.id)')
            ->where('r.email =:val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Entreprise[] Returns an array of Entreprise objects
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
    public function findOneBySomeField($value): ?Entreprise
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
