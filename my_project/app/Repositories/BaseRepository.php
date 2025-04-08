<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

abstract class BaseRepository implements IRepository
{
    protected ?Model $model = null;

    public function __construct()
    {
        $this->setModel();
        if (!$this->model) {
            // ghi log o day khi co loi ...
            throw new RuntimeException("Model has not been set in the repository");
        }
    }

    abstract protected function setModel();

    public function getById($id): ?Model
    {
        return $this->model->find($id);
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getAllPagingAndSort($sortBy, $order): LengthAwarePaginator
    {
        if ($sortBy === 'full_name') {
            return $this->model::sortByFullName($order)->paginate(5);
        }

        return $this->model::orderBy($sortBy, $order)->paginate(5);
    }

    public function create(array $requestData): ?Model
    {
        return $this->model->create($requestData);
    }

    public function update($id, array $requestData): bool
    {
        $item = $this->model->findOrFail($id);
        return !empty($requestData) ? $item->update($requestData) : false;
    }

    public function delete($id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }


    // search ...
    public function searchPagingAndSort(array $requestData, $sort = 'id', $direction = 'desc', $perPage = 5)
    {
        $query = $this->model->query();

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
        $columns = Schema::getColumnListing($this->model->getTable());

        if ($sort && in_array($sort, $columns, true)) {
            $query->orderBy($sort, strtolower($direction));
        }

        return $query;
    }

    public function search(array $requestData, $query)
    {
        if (!empty($requestData['full_name'])) {
            // Tìm kiếm theo full_name (ghép first_name + last_name)
            $query->searchFullName($requestData['full_name']);
        }

        // thêm query theo số lượng trường được tìm kiếm
        foreach ($requestData as $key => $value) {
            if ($value !== null && $value !== '' && $key !== 'full_name') {
                $query->where($key, 'like', '%' . $value . '%');
            }
        }

        return $query;
    }
}
