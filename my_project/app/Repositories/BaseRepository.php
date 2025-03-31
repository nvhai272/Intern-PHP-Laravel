<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RuntimeException;

abstract class BaseRepository implements IRepository
{
    protected ?Model $model = null;

    public function __construct()
    {
        $this->setModel();
        if (!$this->model) {
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

    public function searchPagingAndSort($amount, array $requestData, $sort = null, $direction = 'asc')
    {
        $filters = array_filter($requestData, fn($value) => $value !== null && $value !== '');
        $columns = \Schema::getColumnListing($this->model->getTable());
        $query = $this->model->query();

        foreach ($filters as $key => $value) {
            if ($key === 'name' && method_exists($this->model, 'searchName')) {
                $query->searchName($value);
            } elseif (in_array($key, $columns)) {
                $query->where($key, 'like', '%' . $value . '%');
            }
        }

        if ($sort && in_array($sort, $columns)) {
            $query->orderBy($sort, strtolower($direction));
        }

        return $query->paginate($amount);
    }
}
