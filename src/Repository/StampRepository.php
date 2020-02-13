<?php

namespace App\Repository;

use App\Entity\Stamp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

/**
 * @method Stamp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stamp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stamp[]    findAll()
 * @method Stamp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StampRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stamp::class);
    }

    public function findAllByUsers(Collection $users)
    {
        $qb = $this->createQueryBuilder('stamp');

        return $qb->select('stamp')
            ->where('stamp.user in (:following)')
            ->setParameter('following', $users)
            ->orderBy('stamp.time', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByCategory($category)
    {
        $qb = $this->createQueryBuilder('category');

        return $qb->select('stamp')
            ->where('category.user = :user')
            ->setParameter('category', $category)
            ->orderBy('category.name', 'ASC');


//            ->where('stamp.user in (:following)')
//            ->setParameter('following', $users)
//            ->orderBy('stamp.time', 'DESC')
//            ->getQuery()
//            ->getResult();
    }

    // /**
    //  * @return Stamp[] Returns an array of Stamp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stamp
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
