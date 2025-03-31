<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class EmployeeRepository extends BaseRepository implements IEmployeeRepository
{
    protected function setModel(): void
    {
        $this->model = new Employee();
    }
    public function getTableName(): string
    {
        return $this->model->getTable();
    }

    public function findNotActiveEmployeeByEmail($email)
    {
        // TODO: Implement findNotActiveEmployeeByEmail() method.
    }

    public function findActiveEmployeeByEmail($email)
    {
        // TODO: Implement findActiveEmployeeByEmail() method.
    }

    public function findAllEmployeeId()
    {
        // TODO: Implement findAllEmployeeId() method.
    }
}
