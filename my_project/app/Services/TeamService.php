<?php

namespace App\Services;

use App\Repositories\TeamRepository;

class TeamService
{
    protected TeamRepository $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function getAllTeams()
    {
        return $this->teamRepository->getAll();
    }

    public function getTeamById($id)
    {
        return $this->teamRepository->findById($id);
    }
}
