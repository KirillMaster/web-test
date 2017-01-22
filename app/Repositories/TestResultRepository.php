<?php

namespace Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use League\Flysystem\Exception;
use PaginationResult;
use Test;
use TestResult;
use User;

class TestResultRepository extends BaseRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, TestResult::class);
    }

    public function getLastAttemptNumber($testId, $userId)
    {
        $qb = $this->repo->createQueryBuilder('tr');
        $countQuery = $qb->where('tr.user = ' .$userId.'AND tr.test = '.$testId)
            ->select($qb->expr()->count('tr.id'))
            ->getQuery();

        return $countQuery->getSingleScalarResult();
    }

    public function getByGroupAndTest($testId, $groupId)
    {
        $maxQb = $this->repo->createQueryBuilder('tr');
        $maxQuery = $maxQb->select('MAX(tr.dateTime)')
            ->join(\StudentGroup::class, 'sg', Join::WITH, 'sg.group = :groupId')
            ->setParameter('groupId', $groupId)
            ->join(User::class, 'u', Join::WITH, 'tr.user = u.id AND sg.student = u.id')
            ->where('tr.test = :testId')
            ->setParameter('testId', $testId)
            ->groupBy('tr.user, tr.test')
            ->getQuery();

        $maxDateTimes = $maxQuery->execute();
        $qb = $this->repo->createQueryBuilder('tr');
        $query = $qb->join(\StudentGroup::class, 'sg', Join::WITH, 'sg.group = :groupId')
            ->setParameter('groupId', $groupId)
            ->join(User::class, 'u', Join::WITH, 'tr.user = u.id AND sg.student = u.id')
            ->where($qb->expr()->in('tr.dateTime', '?1'))
            ->setParameter(1,$maxDateTimes)
            ->orderBy('u.lastname')
            ->getQuery();

        return $query->execute();
    }

    public function getByUserAndDiscipline($userId, $disciplineId){
        $maxQb = $this->repo->createQueryBuilder('tr');
        $maxQuery = $maxQb->select('MAX(tr.dateTime)')
            ->join(Test::class, 't', Join::WITH, 't.discipline = :discipline')
            ->where('tr.user = :userId')
            ->setParameter('discipline', $disciplineId)
            ->setParameter('userId', $userId)
            ->groupBy('tr.user, tr.test')
            ->getQuery();

        $maxDateTimes = $maxQuery->execute();
        $qb = $this->repo->createQueryBuilder('tr');
        $query = $qb->join(Test::class, 't', Join::WITH, "t.discipline = $disciplineId")
            ->where("tr.user = $userId")
            ->where($qb->expr()->in('tr.dateTime', '?1'))
            ->setParameter(1,$maxDateTimes)
            ->getQuery();

        return $query->execute();
    }

    public function getLastForUser($userId, $testId){
        try{
            $qb = $this->repo->createQueryBuilder('tr');
            $query = $qb->where('tr.user = :user AND tr.test = :test')
                ->setParameter('user', $userId)
                ->setParameter('test', $testId)
                ->orderBy('tr.attempt', 'DESC')
                ->setMaxResults(1)
                ->getQuery();

            return $query->getOneOrNullResult();
        } catch (Exception $exception){
            return null;
        }
    }

    public function getByUserAndTest($userId, $testId){
        return $this->repo->findBy(['user' => $userId, 'test' => $testId]);
    }

    public function deleteOlderThan($dateTime){
        $qb = $this->repo->createQueryBuilder('tr');
        $deleteQuery = $qb->delete()
            ->where('tr.dateTime < :dateTime')
            ->setParameter('dateTime', $dateTime)
            ->getQuery();

        return $deleteQuery->execute();
    }
}