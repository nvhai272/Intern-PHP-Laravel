<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\ITaskRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class TaskRepository implements ITaskRepository
{
    public function getById($id): ?Model
    {
        return Task::find($id);
    }

    public function getAll(): Collection
    {
        return Task::all();
    }

    public function getAllPagingAndSort($sortBy, $order): LengthAwarePaginator
    {
        $query = Task::query();
        if ($sortBy === 'project-name') {
            $query = $query
                ->join('projects', 'tasks.project_id', '=', 'projects.id')
                ->orderBy('projects.name', strtolower($order))
                ->select('tasks.*')
                ->with('project');
        } else {
            $query = $query->orderBy($sortBy, strtolower($order))
                ->with('project');
        }

        return $query->paginate(5);
    }

    public function create(array $requestData): ?Model
    {
        return Task::create($requestData);
    }

    public function update($id, array $requestData): bool
    {
        $pro = Task::findOrFail($id);
        return !empty($requestData) ? $pro->update($requestData) : false;
    }

    public function delete($id): bool
    {
        return Task::findOrFail($id)->delete();
    }

    public function searchPagingAndSort(array $requestData, $sort = 'id', $direction = 'desc', $perPage = 5)
    {
        $query = Task::query()->with('project');

        // Tìm kiếm
        $query = $this->search($requestData, $query);

        // Sắp xếp
        $query = $this->sort($query, $sort, $direction);

        // Phân trang
        return $this->paginate($query, $perPage);
    }


    public function paginate($query, $perPage = 5)
    {
        return $query->paginate($perPage);
    }

    public function sort($query, $sort = 'id', $direction = 'desc')
    {
        $columns = Schema::getColumnListing((new Task())->getTable()
        );



        if ($sort && in_array($sort, $columns, true)) {
            $query->orderBy($sort, strtolower($direction));
        }

        return $query;
    }

    public function search(array $requestData, $query)
    {
        foreach ($requestData as $key => $value) {
            if ($value !== null && $value !== '') {
                $query->where($key, 'like', '%' . $value . '%');
            }
        }

        return $query;
    }
}
