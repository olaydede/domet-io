<?php

namespace App\Repository;

use App\Entity\Domet;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Domet>
 *
 * @method Domet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Domet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Domet[]    findAll()
 * @method Domet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DometRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Domet::class);
    }

    public function save(Domet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Domet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Task $task
     * @return Domet|null
     * @throws NonUniqueResultException
     */
    public function findLatestByTask(Task $task): null|Domet
    {
        return $this->createQueryBuilder('d')
            ->where('d.task = :task')
            ->orderBy('d.createdAt', 'DESC')
            ->setParameter('task', $task)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Domet[] Returns an array of Domet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Domet
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
