<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\TeamProject;
use App\Repositories\Interfaces\IProjectRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class ProjectRepository implements IProjectRepository
{
    public function getById($id): ?Model
    {
        return Project::find($id);
    }

    public function getAll(): Collection
    {
        return Project::all();
    }

    public function getAllPagingAndSort($sortBy, $order): LengthAwarePaginator
    {
        return Project::orderBy($sortBy, $order)->paginate(5);
    }

    public function create(array $requestData): ?Model
    {
        return Project::create($requestData);
    }

    public function update($id, array $requestData): bool
    {
        $pro = Project::findOrFail($id);
        return !empty($requestData) ? $pro->update($requestData) : false;
    }

    public function delete($id): bool
    {
        return Project::findOrFail($id)->delete();
    }

    public function searchPagingAndSort(array $requestData, $sort = 'id', $direction = 'desc', $perPage = 5)
    {
        $query = Project::query();

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
        $columns = Schema::getColumnListing((new Project())->getTable()
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

    public function listTeamOfProject($projectId, $sortBy = 'id', $order = 'asc')
    {

        return TeamProject::where('project_id', $projectId)
            ->orderBy($sortBy, $order)
            ->paginate(5);
    }
    // public function listEmpOfProject();
    // public function addEmpToProject();
    // public function addTeamToProject();
}
