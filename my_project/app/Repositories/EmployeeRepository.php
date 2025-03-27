<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Repositories\BaseRepository;
use Exception;

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
        try {
            return Employee::withoutGlobalScopes()
                ->where('email', $email)
                ->where('del_flag', IS_DELETED)
                ->first();
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function findActiveEmployeeByEmail($email)
    {
        try {
            return Employee::withoutGlobalScopes()
                ->where('email', $email)
                ->where('del_flag', IS_NOT_DELETED)
                ->first();
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function findAllEmployeeId()
    {
        try {
            return Employee::all()->pluck('id')->toArray();
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }
    }
    public function findAllSearchedId(array $requestData, $sort = null, $direction = 'asc')
    {
        try {
            $filters = array_filter(
                $requestData,
                fn($value) => $value !== null && $value !== ''
            );
            $columns = \Schema::getColumnListing((new Employee())->getTable());
            $query = Employee::query();
            foreach ($filters as $key => $value) {
                if ($key === 'name') {
                    $query->searchName($value);
                }
                if (in_array(strtolower($key), $columns)) {
                    if ($key === 'email') {
                        $query->where($key, 'like', '%' . $value . '%');
                    }
                    if ($key === 'team_id') {
                        $query->where($key, $value);
                    }
                }
            }

            if ($sort === 'name') {
                $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$direction}");
            } else {
                if ($sort && in_array(strtolower($sort), $columns)) {
                    $query->orderBy($sort, strtolower($direction));
                }
            }
            return $query->pluck("id")->toArray();

        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return null;
        }

    }
}
