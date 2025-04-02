<?php

namespace App\Repositories\Interfaces;

interface IRepository
{
    public function getById($id);
    public function getAllPagingAndSort($sortBy, $order);
    public function create(array $requestData);
    public function update($id, array $requestData);
    public function delete($id);
    public function searchPagingAndSort(array $requestData, $sort, $direction, $perPage);
}
