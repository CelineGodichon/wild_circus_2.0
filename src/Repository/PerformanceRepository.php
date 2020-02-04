<?php

namespace App\Repository;

use App\Entity\Performance;
use App\Entity\PerformanceSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Performance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Performance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Performance[]    findAll()
 * @method Performance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerformanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Performance::class);
    }


    public function findPerformanceSearchQuery(PerformanceSearch $search): Query
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');

        if ($search->getCity()) {
            $query = $query
                ->innerJoin('p.city', 'c')
                ->addSelect('c')
                ->andWhere('p.city = :cityId')
                ->setParameter('cityId', $search->getCity()->getId());
        }
        if ($search->getSearchText()) {
            $words = explode(' ', $search->getSearchText());
            $clauses = '';
            $parameters = [];

            $i = 0;
            foreach ($words as $word) {
                $parameters[':val' . $i] = '%' . $word . '%';
                if ($i === 0) {
                    $clauses = 'p.name LIKE :val' . $i .
                        ' OR p.description LIKE :val' . $i;
                } else {
                    $clauses .= ' AND p.name LIKE :val' . $i .
                        ' OR p.description LIKE :val' . $i;
                }
                $i++;
            }
            $query = $query
                ->andWhere('p.name LIKE :name OR p.description LIKE :description')
                ->setParameter('name', '%' . $search->getSearchText() . '%')
                ->setParameter('description', '%' . $search->getSearchText() . '%');
        }
        return $query->getQuery();
    }

    // /**
    //  * @return Performance[] Returns an array of Performance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Performance
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
