<?php

namespace App\Repository;

use App\Entity\Topic;
use App\Pagination\Paginator;
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


    public function findAllTopicbyForum(int $idForum, int $page = 1): Paginator{
        $query = $this->createQueryBuilder('t')
            ->addSelect('u')
            ->innerJoin('t.user', 'u')
            ->where("t.idForum = :id_forum")
            ->andwhere("t.idUser = u.idUser")
            ->setParameter('id_forum', $idForum);

        return (new Paginator($query, 2))->paginate($page);
    }


    /**
     * @return Topic
     */
    public function findbyTopic(int $idTopic): Topic{
        $query = $this->createQueryBuilder('t')
            ->addSelect('p', 'u')
            ->innerJoin('t.posts', 'p')
            ->innerJoin('t.user', 'u')
            ->where("t.idTopic = :id_topic")
            ->andwhere("t.idTopic = p.idTopic")
            ->andwhere("t.idUser = u.idUser")
            ->setParameter('id_topic', $idTopic)
            ->getQuery();

        return $query->getSingleResult();
    }

}
