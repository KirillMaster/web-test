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

    public function transferToNextCourse() {
        try {
            $studentIds = (array) Input::get("studentIds");
            $group = $this->_studentService->transferToNextCourse($studentIds);
            return $this->ok($group);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}