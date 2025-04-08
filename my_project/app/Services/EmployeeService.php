<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Team;
use App\Repositories\EmployeeRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            throw new Exception(ERROR_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . " {$this->table} has id {$id} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function getAllEmployees($sortBy = 'id', $order = 'desc')
    {
        try {
            //            return $this->empRepo->getAll();
            return $this->empRepo->getAllPagingAndSort($sortBy, $order);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . "{$this->table} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function searchEmployee(Request $request)
    {
        $teams = Team::all();
        $data = $request->only(['full_name', 'email', 'team_id']);
        $sortBy = $request->input('sort_by', 'id');
        $order = $request->input('order', 'desc');

        try {
            if ($request->has('search')) {
                $emps = $this->empRepo->searchPagingAndSort($data, $sortBy, $order)->appends([
                    'full_name' => $request->input('full_name'),
                    'email' => $request->input('email'),
                    'team_id' => $request->input('team_id'),
                    'sort_by' => $sortBy,
                    'order' => $order,
                ]);
            } else {
                $emps = $this->getAllEmployees();
            }

            return [
                'teams' => $teams,
                'emps' => $emps ?: collect(),
                'data' => $data,
                'sortBy' => $sortBy,
                'order' => $order,
            ];
        } catch (Throwable $e) {
            Log::error(ERROR_SEARCH  . ' $e->getMessage()');
        }
    }
    public function getDataExportFileCSV($data, $sortBy, $order)
    {
        try {
            // tạo đối tượng query -> Builder để truy vấn
            $query = Employee::query();

            $this->empRepo->search($data, $query);

            $this->empRepo->sort($query, $sortBy, $order);

            $data = $query->get();

            return $data;
        } catch (Throwable $e) {
            Log::error(ERR_EXPORT_FILE  . ' $e->getMessage()');
        }
    }

    public function createEmp(array $data)
    {
        try {
            // dd($data);
            $res = $this->empRepo->create(requestData: $data);
            if ($res) {
                Storage::move('temp_avatars/' . $data['avatar'], 'avatars/' . $data['avatar']);
                Log::info(CREATE_SUCCEED . " {$this->table}", ['data' => $data]);
                session()->forget('dataCreateEmp');
            }
        } catch (QueryException $e) {
            dd($e);
            Log::error(message: ERROR_DATABASE . " {$this->table} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {

            dd($e);
            Log::error(ERROR_CREATE_FAILED . " {$this->table} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function updateEmp($id, array $data)
    {
        try {
            $res = $this->empRepo->update($id, $data);
            if ($res) {
                Log::info(UPDATE_SUCCEED . " {$this->table} has id {$id}");

                // dd($data['avatar']);
                // dd(session('current_avatar'));


                if (!$data['avatar'] === session('current_avatar')) {
                    Storage::move('temp_avatars/' . $data['avatar'], 'avatars/' . $data['avatar']);
                    Storage::delete('avatars/' . session('current_avatar'));
                }
                Storage::move('temp_avatars/' . $data['avatar'], 'avatars/' . $data['avatar']);

                session()->forget('dataEditTeam');
                session()->forget('new_avatar');
                session()->forget('current_avatar');
            }
            return $res;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_UPDATE_FAILED . "{$this->table} with id {$id} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function deleteEmp($id)
    {
        try {
            $res = $this->empRepo->delete($id);
            if ($res) {
                Log::info(DELETE_SUCCEED . " {$this->table} has id {$id}");
            }
            return $res;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_DELETE_FAILED . "{$this->table} with id {$id} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }
}
