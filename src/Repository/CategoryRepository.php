<?php

namespace App\Repository;

use App\Entity\Category;
use App\Pagination\Paginator;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function findAllCategories(): array{
        $query = $this->createQueryBuilder('c')
            ->addSelect('f')
            ->where("c.active = 1")
            ->innerJoin('c.forums', 'f')
            ->andwhere("c.idCategory = f.idCategory")
            ->orderBy('c.dateAdd', 'DESC')
            ->getQuery();

        return $query->getResult();
    }


    public function findLatest(int $page = 1, string $text = null, DateTime $startDate = null, DateTime $endDate = null): Paginator
    {
        $qb = $this->createQueryBuilder('l')
            ->orderBy('l.created_at', 'DESC');


        if (null != $text) {
            $qb->andWhere('l.url LIKE :text')
                ->setParameter('text', '%'.$text.'%');
        }
        if (null != $startDate && null != $endDate) {
            $qb->andWhere('l.created_at between :startDate and :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        }

        return (new Paginator($qb))->paginate($page);
    }



    // /**
    //  * @return Link[] Returns an array of Link objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Link
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
