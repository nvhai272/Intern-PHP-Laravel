<?php

namespace App\Repositories;

use App\Models\Team;

class TeamRepository
{
    protected Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->team->all();
    }

    public function findById($id)
    {

        return $this->team->findOrFail($id) ;
    }
}
