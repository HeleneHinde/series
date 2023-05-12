<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function save(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBestSeries()
    {
        //en DQL
//
//        $entityManager = $this->getEntityManager();
//
//        $dql = "select s from App\Entity\Serie as s where s.vote>8 and s.popularity>100 order by s.popularity DESC";
//
//        $query = $entityManager->createQuery($dql);
//

        //Avec le Query Builder

        $qb = $this->createQueryBuilder('s');

        $qb ->andWhere('s.vote>8')
            ->andWhere('s.popularity>100')
            ->addOrderBy('s.popularity', 'DESC');

        $query = $qb->getQuery();

        //Pareil pour les 2
        $query->setMaxResults(Serie::MAX_RESULT);

        return $query->getResult();




    }

    public function findSeriesWithPagination(int $page){

        $qb=$this->createQueryBuilder('s');
        $qb->addOrderBy('s.popularity', 'DESC');

        $query= $qb->getQuery();
        $query->setMaxResults(Serie::MAX_RESULT);

        //limite début d'affichage (ex (1-1)*48 = 0, (2-1)*48=48, etc...
        $offset = ($page-1)*Serie::MAX_RESULT;
        $query->setFirstResult($offset);

        return $query->getResult();
    }


//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
