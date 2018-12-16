<?php

namespace Services;

use Repositories\UnitOfWork;

class GroupService
{
    private $_unitOfWork;

    public function __construct(UnitOfWork $unitOfWork)
    {
        $this->_unitOfWork = $unitOfWork;
    }

    public function getByYear($year) {
        return $this->_unitOfWork->groups()
            ->getByYear($year);
    }
}