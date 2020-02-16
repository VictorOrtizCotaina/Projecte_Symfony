<?php

namespace App\Repository;

use App\Entity\Forum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Forum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forum[]    findAll()
 * @method Forum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }

    /**
     * @return Forum[]
     */
    public function findAllForum(): array{
        $query = $this->createQueryBuilder('f')
            ->addSelect('c')
            ->innerJoin('f.idCategory', 'c')
            ->andwhere("c.idCategory = f.idCategory")
            ->andwhere("c.active = 1")
            ->orderBy('c.dateAdd', 'DESC')
            ->getQuery();

        return $query->getResult();
    }
}
