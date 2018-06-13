<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function search($query)
    {

        return $this->createQueryBuilder('u')
            ->select('u.id', 'p.firstname', 'p.lastname', 'u.email')
            ->addSelect("MATCH_AGAINST (u.email, :search 'IN BOOLEAN MODE') as uscore")
            ->addSelect("MATCH_AGAINST (p.firstname, p.lastname, :search 'IN BOOLEAN MODE') as pscore")
            ->join("u.profile", 'p', 'WITH', 'u.profile = p.id')
            ->add('where', 'MATCH_AGAINST(u.email, :search) > 0')
            ->add('where', 'MATCH_AGAINST(p.firstname, p.lastname, :search) > 0')
            ->setParameter('search', '*'.$query.'*')
            ->orderBy('uscore, pscore', 'desc')
            ->getQuery()
            ->getScalarResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?User
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
