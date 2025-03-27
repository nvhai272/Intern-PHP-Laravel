<?php

namespace App\Repositories\Interfaces;

interface IEmployeeRepository
{
    public function findNotActiveEmployeeByEmail($email);
    public function findActiveEmployeeByEmail($email);
    public function findAllEmployeeId();
}
