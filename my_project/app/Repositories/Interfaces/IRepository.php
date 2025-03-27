<?php

namespace App\Repositories\Interfaces;

interface IRepository
{
    public function getById($id);
    public function getAllPaging($amount);
    public function create(array $requestData);
    public function update($id, array $requestData);
    public function delete($id);
    public function searchPaging($amount, array $requestData);
}
