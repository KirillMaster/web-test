<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 04.11.16
 * Time: 1:43
 */

namespace Managers;

use DateTime;
use Exception;
use Repositories\UnitOfWork;
use TestEngine\GlobalTestSettings;

class ScheduleManager
{
    private $_unitOfWork;

    public function __construct(UnitOfWork $unitOfWork)
    {
        $this->_unitOfWork = $unitOfWork;
    }

    /**
     * Определение номера текущего семестра для группы.
     * @param $groupId
     * @return mixed
     */
    public function getCurrentSemesterForGroup($groupId){
        $group = $this->_unitOfWork->groups()->find($groupId);
        $groupStudyYear = $group->getCourse();
        $now = new DateTime();

        $currentMounthNumber = date("n", $now->getTimestamp()) + 1;
        $currentYearSemester = ($currentMounthNumber >= GlobalTestSettings::secondSemesterMounth
            && $currentMounthNumber < GlobalTestSettings::firstSemesterMounth) ? 2 : 1;

        return ($groupStudyYear - 1) * 2 + $currentYearSemester;
    }

    public function getCurrentSemesterForUser($userId){
        $userGroup = $this->_unitOfWork->studentGroups()->getUserGroup($userId);
        if ($userGroup == null || $userGroup->getGroup() == null){
            throw new Exception("Не найдена группа текущего пользователя!");
        }
        $groupId = $userGroup->getGroup()->getId();

        return $this->getCurrentSemesterForGroup($groupId);
    }
}