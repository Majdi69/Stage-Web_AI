<?php

namespace App\Repository;

use App\Entity\SubChapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubChapter>
 *
 * @method SubChapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubChapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubChapter[]    findAll()
 * @method SubChapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubChapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubChapter::class);
    }

//    /**
//     * @return SubChapter[] Returns an array of SubChapter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SubChapter
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
