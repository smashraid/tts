<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Schedule;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Schedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Schedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Schedule[]    findAll()
 * @method Schedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    // /**
    //  * @return Schedule[] Returns an array of Schedule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Schedule
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Schedule[] Returns an array of Schedule objects
     */
    public function findAllFilter($user, $employee, $startDate, $endDate)
    {
        $qb = $this->createQueryBuilder('s')
            //->select(['s.id, s.link', 's.start', 's.timestart', 's.timeend', 's.user', 's.employee'])
            ->innerJoin(User::class, 'u', Join::WITH, 's.user = u.id')
            ->innerJoin(Employee::class, 'e', Join::WITH, 's.employee = e.id');

        if ($user != null) {
            $qb->andWhere('u.lastname LIKE :searchTermU')
                ->setParameter('searchTermU', '%' . $user . '%');
        }

        if ($employee != null) {
            $qb->andWhere('e.name LIKE :searchTermE')
                ->setParameter('searchTermE', '%' . $employee . '%');
        }

        if ($startDate != null && $endDate != null) {
            $qb->andWhere('s.start BETWEEN :from AND :to')
                ->setParameter('from', $startDate)
                ->setParameter('to', $endDate);
        }

        $query = $qb->getQuery();

        return $query->execute();
    }
}
