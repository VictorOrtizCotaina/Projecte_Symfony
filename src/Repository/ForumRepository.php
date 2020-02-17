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
            ->addSelect('t', 'u')
            ->where("f.active = 1")
            ->innerJoin('f.topics', 't')
            ->innerJoin('f.user', 'u')
            ->andwhere("f.idForum = t.idForum")
            ->andwhere("f.idUser = u.idUser")
            ->orderBy('f.dateAdd', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return Forum
     */
    public function findbyForum(int $idForum): Forum{
        $query = $this->createQueryBuilder('f')
            ->addSelect('t', 'u')
            ->innerJoin('f.topics', 't')
            ->innerJoin('f.user', 'u')
            ->where("f.idForum = :id_forum")
            ->andwhere("f.idForum = t.idForum")
            ->andwhere("f.idUser = u.idUser")
            ->setParameter('id_forum', $idForum)
            ->getQuery();

        return $query->getSingleResult();
    }

}
