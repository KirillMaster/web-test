<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Managers\TestManager;
use Test;
use Illuminate\Http\Request;
use Managers\LecturerManager;
use TestEngine\QuestionAnswer;
use TestEngine\TestProcessManager;


class TestProcessController extends Controller
{
    private $_testProcessManager;

    public function __construct(TestProcessManager $testProcessManager)
    {
        $this->_testProcessManager = $testProcessManager;
    }

    /*
     * Инициализация процесса тестирования.
     * Простановка в переменных сессии браузера идентификатора сессии тестирования.
     */
    public function startTest(Request $request){
        try{
            $result = null;
            $testId = $request->json('testId');
            $currentUser = Auth::user();
            if (isset($currentUser)){
                $userId = $currentUser->getId();
                $result = $this->_testProcessManager->initTest($userId, $testId);
                $request->session()->set('sessionId', $result);

                return $this->successJSONResponse(['sessionId' => $result]);
            } else {
                throw new Exception('Для начала тестирования необходимо авторизоваться!');
            }
        } catch (Exception $exception){
            return $this->faultJSONResponse($exception->getMessage());
        }
    }

    /*
     * Получение следующего вопроса теста.
     * [!] В случае окончания теста в качестве ответа может быть получен
     * результат теста вместо следующего вопроса.
     */
    public function getNextQuestion(Request $request){
        try{
            $sessionId = $request->session()->get('sessionId');
            $nextQuestionRequestResult = $this->_testProcessManager->getNextQuestion($sessionId);

            return $this->successJSONResponse($nextQuestionRequestResult);
        } catch (Exception $exception){
            return $this->faultJSONResponse($exception->getMessage());
        }
    }

    /*
     * Обработка ответа на вопрос теста.
     * В зависимости от типа вопроса, принимаются Id выбранных ответов (answersIds)
     * или текст ответа (answerText).
     */
    public function answer(Request $request){
        try{
            $sessionId = $request->session()->get('sessionId');
            $questionId = $request->json('questionId');
            $answersIds = $request->json('answerIds');
            $answerText = $request->json('answerText');

            $questionAnswer = new QuestionAnswer();
            $questionAnswer->setQuestionId($questionId);
            $questionAnswer->setAnswerIds($answersIds);
            $questionAnswer->setAnswerText($answerText);

            $result = $this->_testProcessManager->processAnswer($sessionId, $questionAnswer);

            return $this->successJSONResponse($result);
        } catch (Exception $exception){
            return $this->faultJSONResponse($exception->getMessage());
        }
    }
}
