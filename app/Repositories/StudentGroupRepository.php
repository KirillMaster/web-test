<?php

namespace Repositories;

use Doctrine\ORM\EntityManager;
use StudentGroup;

class StudentGroupRepository extends BaseRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, StudentGroup::class);
    }
    
    public function getUserGroup($userId) {
        return $this->repo->findOneBy(['student' => $userId]);
    }

    public function getByStudentId($studentId) {
        return $this->repo->findOneBy(["student" => $studentId]);
    }

    public function clearUserGroup($userId){
        $qb = $this->repo->createQueryBuilder('sg');
        $deleteQuery = $qb->delete()
            ->where('sg.student = :student')
            ->setParameter('student', $userId)
            ->getQuery();

        return $deleteQuery->execute();
    }
}
