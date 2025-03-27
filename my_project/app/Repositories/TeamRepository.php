<?php

namespace App\Repositories;

use App\Models\Team;
use App\Repositories\BaseRepository;

class TeamRepository extends BaseRepository
{
    protected function setModel(): void
    {
        $this->model = new Team();
    }
    public function getTableName(): string
    {
        return $this->model->getTable();
    }
}
