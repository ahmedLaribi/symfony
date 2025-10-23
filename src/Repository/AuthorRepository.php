<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    //    /**
    //     * @return Author[] Returns an array of Author objects
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

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function showAllQB(){
        return $this->createQueryBuilder('a')
                    ->andWhere ('a.email',':condition')
                    ->setParameter('condition', 'LIKE %a%')
                    ->orderBy('a.username', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    public function ShowAllAuthorDQL(){
        $query= $this->getEntityManager()
              ->createQuery('SELECT a FROM app\Entity\Author a WHERE a.username LIKE :condition ORDERBY a.username ASC ')
              ->setParameter('condition', '%a%');
        return $query->getResult();
    }
}
