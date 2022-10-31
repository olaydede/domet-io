<?php

namespace App\Repository;

use App\Entity\Domet;
use App\Entity\Task;
use App\Enum\DometStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

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
    public function findLatestIncompleteDometByTask(Task $task): null|Domet
    {
        return $this->createQueryBuilder('d')
            ->where('d.task = :task')
            ->andWhere('d.status = :status')
            ->orderBy('d.createdAt', 'DESC')
            ->setParameter('task', $task)
            ->setParameter('status', DometStatus::IN_PROGRESS->value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
