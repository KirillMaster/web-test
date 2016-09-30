<?php

namespace Repositories;

use ProAI\Datamapper\EntityManager;
use Repositories\Interfaces\IGroupRepository;
use Repositories\Interfaces\IProfileRepository;

class ProfileRepository extends BaseRepository implements IProfileRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, 'Profile');
    }
}