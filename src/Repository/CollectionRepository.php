<?php

namespace App\Repository;

use App\Entity\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Collection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collection[]    findAll()
 * @method Collection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collection::class);
    }

    public function findAllByUser($user)
    {
        $qb = $this->createQueryBuilder('collection');

        return $qb->select('collection')
            ->where('collection.user = :user')
            ->setParameter('user', $user)
            ->orderBy('collection.collection', 'ASC');
    }

    public function findAllPublicByUser($user)
    {
        $qb = $this->createQueryBuilder('collection');

        return $qb->select('collection')
            ->where('collection.user = :user')
            ->andWhere('collection.status = :public')
            ->setParameter('user', $user)
            ->setParameter('public', 'public')
            ->orderBy('collection.collection', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllPrivateByUser($user)
    {
        $qb = $this->createQueryBuilder('collection');

        return $qb->select('collection')
            ->where('collection.user = :user')
            ->andWhere('collection.status = :private')
            ->setParameter('private', 'private')
            ->setParameter('user', $user)
            ->orderBy('collection.collection', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}