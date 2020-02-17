<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    /**
     * @return Topic[]
     */
    public function findAllTopic(): array{
        $query = $this->createQueryBuilder('f')
            ->addSelect('c')
            ->innerJoin('f.idCategory', 'c')
            ->andwhere("c.idCategory = f.idCategory")
            ->andwhere("c.active = 1")
            ->orderBy('c.dateAdd', 'DESC')
            ->getQuery();

        return $query->getResult();
    }


    /**
     * @return Topic
     */
    public function findbyTopic(string $idTopic): array{
        $query = $this->createQueryBuilder('t')
            ->addSelect('p', 'u')
            ->innerJoin('t.posts', 'p')
            ->innerJoin('t.user', 'u')
            ->where("t.idTopic = :id_topic")
            ->andwhere("t.idTopic = p.idTopic")
            ->andwhere("t.idUser = u.idUser")
            ->setParameter('id_topic', $idTopic)
            ->getQuery();

        return $query->getResult();
    }

}