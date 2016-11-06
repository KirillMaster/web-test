<?php

namespace Managers;

use Answer;
use GivenAnswer;
use QuestionViewModel;
use Repositories\UnitOfWork;
use Question;

class QuestionManager
{
    private $_unitOfWork;

    public function __construct(UnitOfWork $unitOfWork)
    {
        $this->_unitOfWork = $unitOfWork;
    }

    public function getByParamsPaginated($pageSize, $pageNum,
                                               $themeId, $text, $type, $complexity){
        return $this->_unitOfWork
            ->questions()
            ->getByParamsPaginated($pageSize, $pageNum,
                $themeId, $text, $type, $complexity);
    }

    public function create(Question $question, $themeId, array $answers = null){
        $theme = $this->_unitOfWork->themes()->find($themeId);
        $question->setTheme($theme);
        $this->_unitOfWork->questions()->create($question);
        $this->_unitOfWork->commit();

        foreach ($answers as $answer){
            $newAnswer = new Answer();
            $newAnswer->setText($answer['text']);
            $newAnswer->setIsRight($answer['isRight']);
            $newAnswer->setQuestion($question);

            $this->_unitOfWork->answers()->create($newAnswer);
        }

        $this->_unitOfWork->commit();
    }

    public function update(Question $question, $themeId, array $answers = null){
        $theme = $this->_unitOfWork->themes()->find($themeId);
        $question->setTheme($theme);
        $this->_unitOfWork->questions()->update($question);
        $this->_unitOfWork->commit();

        $question = $this->_unitOfWork->questions()->find($question->getId());

        $this->_unitOfWork->answers()->deleteQuestionAnswers($question->getId());

        foreach ($answers as $answer){
            $newAnswer = new Answer();
            $newAnswer->setText($answer['text']);
            $newAnswer->setIsRight($answer['isRight']);
            $newAnswer->setQuestion($question);

            $this->_unitOfWork->answers()->create($newAnswer);
        }

        $this->_unitOfWork->commit();
    }

    public function delete($id){
        $question = $this->_unitOfWork->questions()->find($id);
        if ($question != null){
            $this->_unitOfWork->questions()->delete($question);
        }
        $this->_unitOfWork->commit();
    }

    /**
     * Получение вопроса с ответами.
     * @param $questionId
     * @return QuestionViewModel
     */
    public function getWithAnswers($questionId){
        $question = $this->_unitOfWork->questions()->find($questionId);
        $answers = $this->_unitOfWork->answers()->getByQuestion($questionId);

        return new QuestionViewModel($question, $answers);
    }

    public function getById($id){
        return $this->_unitOfWork->questions()->find($id);
    }

    public function createQuestionAnswer(GivenAnswer $givenAnswer){
        $this->_unitOfWork->givenAnswers()->create($givenAnswer);
        $this->_unitOfWork->commit();
    }

}