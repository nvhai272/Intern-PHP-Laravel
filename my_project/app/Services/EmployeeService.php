<?php
namespace App\Services;

use App\Repositories\EmployeeRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class EmployeeService
{
    protected EmployeeRepository $empRepo;
    private string $table;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->empRepo = $employeeRepository;
        $this->table   = $this->empRepo->getTableName();
    }

    public function getEmployeeById($id)
    {
        if (!is_numeric($id)) {
            Log::info(ERROR_ID . " {$this->table} has id {$id}");
            throw new InvalidArgumentException(ERROR_ID);
        }
        try {
            $emp = $this->empRepo->getById($id);
            if ($emp === null) {
                Log::error(ERROR_NOT_FOUND . " {$this->table} has id {$id}");
                return null;
            }
            return $emp;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " {$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . " {$this->table} has id {$id} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function getAllEmployees($sortBy = 'id', $order = 'desc')
    {
        try {
//            return $this->empRepo->getAll();
            return $this->empRepo->getAllPagingAndSort($sortBy, $order);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

}
