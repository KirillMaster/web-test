<?php

namespace Services;

use Group;
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

    public function transferToNextCourse($studentIds)
    {
        $groupId = 0;
        $newGroup = null;

        foreach ($studentIds as $studentId)
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

            // TODO: Create constant.
            if ($group->getCourse() >= 4) {
                throw new Exception(
                    "Не удалось перевести студента на следующий курс. " .
                    "Курс студента: ". $group->getCourse() . ". " .
                    "Идентификатор студента: $studentId.");
            }

            $newGroup = ($this->_unitOfWork->groups()->find($groupId) === null)
                ? $this->createNextGroup($group)
                : $this->_unitOfWork->groups()->find($groupId);

            $this->_entityManager->persist($newGroup);
            $this->_entityManager->flush();

            $groupId = $newGroup->getId();

            $studentGroup->setGroup($newGroup);
            $this->_entityManager->flush($studentGroup);
        }

        return $newGroup;
    }

    private function createNextGroup(Group $model) {
        $group = new Group();
        $group->setCourse($model->getCourse() + 1);
        $group->setIsFulltime($model->getIsFulltime());
        $group->setPrefix($model->getPrefix());
        $group->setName($model->getName());
        $group->setNumber($model->getNumber());
        $group->setStudyplan($model->getStudyplan());
        $group->setYear($model->getYear());
        $group->setName($this->generateName($group));
        return $group;
    }

    private function generateName(Group $group) {
        $number = $group->getCourse() . $group->getNumber();
        return  $group->getPrefix() . "-" . $number . $this->getMode($group);
    }

    private function getMode(Group $group) {
        return $group->getIsFulltime() ? "о" : "з";
    }
}