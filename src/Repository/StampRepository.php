<?php

namespace App\Repository;

use App\Entity\Stamp;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;

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

        return $qb->leftJoin('stamp.collection', 'collection')
            ->where('stamp.user in (:following)')
            ->andWhere('collection.status = :public OR stamp.collection IS null')
            ->setParameter('following', $users)
            ->setParameter('public', 'public')
            ->orderBy('stamp.time', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithoutCategoryAndCollection($user)
    {
        $qb = $this->createQueryBuilder('stamp');

        return $qb->select('stamp')
            ->where($qb->expr()->isNull('stamp.collection'))
            ->andWhere($qb->expr()->isNull('stamp.category'))
            ->andWhere('stamp.user = :user')
            ->setParameter('user', $user)
            ->orderBy('stamp.time', 'DESC')
            ->getQuery()
            ->getResult();;
//
//        $qb = $this->createQueryBuilder('collection');
//
//        return $qb->select('collection')
//            ->where('collection.user = :user')
//            ->setParameter('user', $user)
//            ->orderBy('collection.collection', 'ASC');
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
