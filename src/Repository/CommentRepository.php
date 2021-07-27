<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param string|null $term
     * @return QueryBuilder Returns query builder object
     */
    public function getAllWithSearchQueryBuilder(?string $term = null): QueryBuilder
    {
        $query = $this->createQueryBuilder('c')
            ->innerJoin('c.question', 'q')
            ->addSelect('q')
        ;

        if ($term) {
            $query->andWhere('c.text LIKE :term OR c.authorName LIKE :term OR q.name LIKE :term')
                ->setParameter('term', '%'.$term.'%')
            ;
        }

        return $query->orderBy('c.createdAt', 'DESC');
    }


    /*
    public function findOneBySomeField($value): ?Comment
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
