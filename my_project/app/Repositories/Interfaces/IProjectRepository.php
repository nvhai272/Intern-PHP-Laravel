<?php

namespace App\Repositories\Interfaces;

interface IProjectRepository
{
    public function getById($id);
    public function getAllPagingAndSort($sortBy, $order);
    public function create(array $requestData);
    public function update($id, array $requestData);
    public function delete($id);
    public function searchPagingAndSort(array $requestData, $sort, $direction, $perPage = 5);
    public function listTeamOfProject($projectId, $sortBy = 'id', $order = 'asc');
    // public function listEmpOfProject();
    // public function addEmpToProject();
    // public function addTeamToProject();




}
