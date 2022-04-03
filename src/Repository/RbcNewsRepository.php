<?php

namespace App\Repository;

use App\Entity\RbcNews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RbcNews|null find($id, $lockMode = null, $lockVersion = null)
 * @method RbcNews|null findOneBy(array $criteria, array $orderBy = null)
 * @method RbcNews[]    findAll()
 * @method RbcNews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RbcNewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RbcNews::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(RbcNews $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(RbcNews $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function save(RbcNews $rbcNews): void
    {
        $this->_em->persist($rbcNews);
        $this->_em->flush();
    }

    public function update(RbcNews $oldNews, RbcNews $newNews): void
    {
        $updatedNews = $oldNews->update(
            $newNews->getTitle(),
            $newNews->getContent(),
            $newNews->getTimestamp(),
            $newNews->getOriginalImageUrl(),
            $newNews->getImageTitle()
        );

        $this->save($updatedNews);
    }
    // /**
    //  * @return RbcNews[] Returns an array of RbcNews objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RbcNews
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
