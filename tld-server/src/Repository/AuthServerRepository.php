<?php

namespace App\Repository;

use App\Entity\AuthServer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuthServer>
 *
 * @method AuthServer|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthServer|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthServer[]    findAll()
 * @method AuthServer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthServer::class);
    }

//    /**
//     * @return AuthServer[] Returns an array of AuthServer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AuthServer
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
