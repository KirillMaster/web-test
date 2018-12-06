<?php

namespace Services;

use Exception;
use Repositories\UnitOfWork;
use Doctrine\ORM\EntityManager;

class StudentService
{
    private $_entityManager;
    private $_unitOfWork;

    public function __construct(EntityManager $entityManager, UnitOfWork $unitOfWork)
    {
        $this->_entityManager = $entityManager;
        $this->_unitOfWork = $unitOfWork;
    }

    public function transferAllToNextCourse($studentIds) {
        foreach ($studentIds as $id) {
            $this->transferToNextCourse($id);
        }
    }

    public function transferToNextCourse($studentId)
    {
        $student = $this->_unitOfWork->users()
            ->find($studentId);

        if ($student == null) {
            throw new Exception("Студент не найден. Идентификатор студента: $studentId.");
        }

        $studentGroup = $this->_unitOfWork->studentGroups()
            ->getByStudentId($studentId);

        if ($studentGroup == null) {
            throw new Exception("Группа не найдена. Идентификатор студента: $studentId.");
        }

        $group = $studentGroup->getGroup();

        if ($group->getCourse() >= 4) {
            throw new Exception(
                "Не удалось перевести студента на следующий курс. " .
                "Курс студента: ". $group->getCourse() . ". " .
                "Идентификатор студента: $studentId.");
        }

        $group->setCourse($group->getCourse() + 1);
        $this->_entityManager->flush($group);
    }
}