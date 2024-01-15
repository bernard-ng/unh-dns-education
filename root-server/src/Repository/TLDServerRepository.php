<?php

namespace App\Repository;

use App\Entity\TLDServer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TLDServer>
 *
 * @method TLDServer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TLDServer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TLDServer[]    findAll()
 * @method TLDServer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TLDServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TLDServer::class);
    }

//    /**
//     * @return TLDServer[] Returns an array of TLDServer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TLDServer
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
