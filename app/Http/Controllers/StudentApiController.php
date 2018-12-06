<?php

namespace App\Http\Controllers;

use Exception;
use Services\StudentService;
use Illuminate\Support\Facades\Input;

class StudentApiController extends ApiController
{
    private $_studentService;

    public function __construct(StudentService $studentService)
    {
        $this->_studentService = $studentService;
    }

    public function transferAllToNextCourse() {
        try {
            $this->_studentService->transferAllToNextCourse(Input::get("studentIds"));
            return $this->ok();
        } catch (Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function transferToNextCourse($studentId) {
        try {
            $this->_studentService->transferToNextCourse($studentId);
            return $this->ok();
        } catch (Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}