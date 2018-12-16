<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 10.10.16
 * Time: 22:36
 */

namespace Repositories;


use Doctrine\ORM\EntityManager;


class UnitOfWork
{
    private $_em;

    public function __construct(EntityManager $em)
    {
        $this->_em = $em;
    }

    private $_userRepo;
    private $_disciplineRepo;
    private $_disciplinePlanRepo;
    private $_instituteRepo;
    private $_profileRepo;
    private $_studyPlanRepo;
    private $_themeRepo;
    private $_roleUserRepo;
    private $_groupRepo;
    private $_markTypeRepo;
    private $_questionRepo;
    private $_answerRepo;
    private $_testRepo;
    private $_testMarkTypeRepo;
    private $_testResultRepo;
    private $_givenAnswerRepo;
    private $_extraAttemptRepo;
    private $_studentGroupRepo;
    private $_sectionRepo;
    private $_testThemeRepo;
    private $_roleRepo;
    private $_settingsRepo;
    private $_paramsSetRepo;
    private $_programRepo;
    private $_dockerInfoRepo;
    private $_mediaRepo;
    private $_mediableRepo;
    private $_studentAttendanceRepo;
    private $_studentProgressRepo;

    public function users(){
        if ($this->_userRepo == null){
            $this->_userRepo = new UserRepository($this->_em);
        }
        return $this->_userRepo;
    }

    public function paramsSets(){
        if ($this->_paramsSetRepo == null){
            $this->_paramsSetRepo = new ParamsSetRepository($this->_em);
        }
        return $this->_paramsSetRepo;
    }

    public function disciplines(){
        if ($this->_disciplineRepo == null){
            $this->_disciplineRepo = new DisciplineRepository($this->_em);
        }
        return $this->_disciplineRepo;
    }

    public function institutes(){
        if ($this->_instituteRepo == null){
            $this->_instituteRepo = new InstituteRepository($this->_em);
        }
        return $this->_instituteRepo;
    }

    public function disciplinePlans(){
        if ($this->_disciplinePlanRepo == null){
            $this->_disciplinePlanRepo = new DisciplinePlanRepository($this->_em);
        }
        return $this->_disciplinePlanRepo;
    }

    public function profiles(){
        if ($this->_profileRepo == null){
            $this->_profileRepo = new ProfileRepository($this->_em);
        }
        return $this->_profileRepo;
    }

    public function studyPlans(){
        if ($this->_studyPlanRepo == null){
            $this->_studyPlanRepo = new StudyPlanRepository($this->_em);
        }
        return $this->_studyPlanRepo;
    }

    public function studentAttendances(){
        if ($this->_studentAttendanceRepo == null){
            $this->_studentAttendanceRepo = new StudentAttendanceRepository($this->_em);
        }
        return $this->_studentAttendanceRepo;
    }

    public function studentProgresses(){
        if ($this->_studentProgressRepo == null){
            $this->_studentProgressRepo = new StudentProgressRepository($this->_em);
        }
        return $this->_studentProgressRepo;
    }


    public function themes(){
        if ($this->_themeRepo == null){
            $this->_themeRepo = new ThemeRepository($this->_em);
        }
        return $this->_themeRepo;
    }

    public function userRoles(){
        if ($this->_roleUserRepo == null){
            $this->_roleUserRepo = new RoleUserRepository($this->_em);
        }
        return $this->_roleUserRepo;
    }

    public function groups(){
        if ($this->_groupRepo == null){
            $this->_groupRepo = new GroupRepository($this->_em);
        }
        return $this->_groupRepo;
    }

    public function markTypes(){
        if ($this->_markTypeRepo == null){
            $this->_markTypeRepo = new MarkTypeRepository($this->_em);
        }
        return $this->_markTypeRepo;
    }

    public function questions(){
        if ($this->_questionRepo == null){
            $this->_questionRepo = new QuestionRepository($this->_em);
        }
        return $this->_questionRepo;
    }

    public function answers(){
        if ($this->_answerRepo == null){
            $this->_answerRepo = new AnswerRepository($this->_em);
        }
        return $this->_answerRepo;
    }

    public function tests(){
        if ($this->_testRepo == null){
            $this->_testRepo = new TestRepository($this->_em);
        }
        return $this->_testRepo;
    }

    public function markTests(){
        if ($this->_testMarkTypeRepo == null){
            $this->_testMarkTypeRepo = new TestMarkTypeRepository($this->_em);
        }
        return $this->_testMarkTypeRepo;
    }

    public function testResults(){
        if ($this->_testResultRepo == null){
            $this->_testResultRepo = new TestResultRepository($this->_em);
        }
        return $this->_testResultRepo;
    }

    public function givenAnswers(){
        if ($this->_givenAnswerRepo == null){
            $this->_givenAnswerRepo = new GivenAnswerRepository($this->_em);
        }
        return $this->_givenAnswerRepo;
    }

    public function extraAttempts(){
        if ($this->_extraAttemptRepo == null){
            $this->_extraAttemptRepo = new ExtraAttemptRepository($this->_em);
        }
        return $this->_extraAttemptRepo;
    }

    public function studentGroups(){
        if ($this->_studentGroupRepo == null){
            $this->_studentGroupRepo = new StudentGroupRepository($this->_em);
        }
        return $this->_studentGroupRepo;
    }

    public function sections(){
        if ($this->_sectionRepo == null){
            $this->_sectionRepo = new SectionRepository($this->_em);
        }
        return $this->_sectionRepo;
    }

    public function testThemes(){
        if ($this->_testThemeRepo == null){
            $this->_testThemeRepo = new TestThemeRepository($this->_em);
        }
        return $this->_testThemeRepo;
    }

    public function roles(){
        if ($this->_roleRepo == null){
            $this->_roleRepo = new RoleRepository($this->_em);
        }
        return $this->_roleRepo;
    }

    public function settings(){
        if ($this->_settingsRepo == null){
            $this->_settingsRepo = new TestSettingRepository($this->_em);
        }
        return $this->_settingsRepo;
    }

    public function programs(){
        if ($this->_programRepo == null){
            $this->_programRepo = new ProgramRepository($this->_em);
        }
        return $this->_programRepo;
    }

    public function medias(){
        if ($this->_mediaRepo == null){
            $this->_mediaRepo = new MediaRepository($this->_em);
        }
        return $this->_mediaRepo;
    }

    public function mediables(){
        if ($this->_mediableRepo == null){
            $this->_mediableRepo = new MediableRepository($this->_em);
        }
        return $this->_mediableRepo;
    }

    public function dockerInfos(){
        if($this->_dockerInfoRepo == null){
            $this->_dockerInfoRepo = new DockerInfoRepository($this->_em);
        }
        return $this->_dockerInfoRepo;
    }

    /**
     * Применяет к базе данных изменения, сделанные втечение сессии.
     */

    public function commit(){
        $this->_em->flush();
    }

    /**
     * Отсоединяет сущность от контеста БД.
     * @param $entity
     */
    public function detach($entity){
        $this->_em->detach($entity);
    }

    /**
     * Обновляет сущность, полученную из БД.
     * (из-за кэширования ORM некоторых получаемых из БД данных, могут возвращаться неактуальные значения).
     * @param $entity
     */
    public function refresh($entity){
        $this->_em->refresh($entity);
    }
}