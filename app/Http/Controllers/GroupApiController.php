<?php

namespace App\Http\Controllers;

use Exception;
use App\Managers\GroupService;

class GroupApiController extends ApiController
{
    private $_groupService;

    public function __construct(GroupService $groupService)
    {
        $this->_groupService = $groupService;
    }

    public function getByYear($year) {
        try {
            $groups = $this->_groupService->getByYear($year);
            return $this->ok($groups);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}