<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\Interfaces\ITaskRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class TaskService
{
    protected $taskRepository;
    protected const MODEL = 'Task';
    public function __construct(ITaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;

    }

    public function getTaskById($id)
    {
        if (!is_numeric($id)) {
            Log::info(ERROR_ID . " " . self::MODEL . " has id {$id}");
            throw new InvalidArgumentException(ERROR_ID);
        }
        try {
            $pro = $this->taskRepository->getById($id);
            if ($pro === null) {
                Log::error(ERROR_NOT_FOUND . " " . self::MODEL . "  has id {$id}");
                return null;
            }
            return $pro;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " " . self::MODEL . " : " . $e->getMessage());
            throw new RuntimeException(ERROR_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . " " . self::MODEL . " : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function getAllTasks($sortBy = 'id', $order = 'desc')
    {
        try {
            return $this->taskRepository->getAllPagingAndSort($sortBy, $order);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " " . self::MODEL . "  : " . $e->getMessage());
            dd($e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . " " . self::MODEL . "  : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function createTask(array $data)
    {
        try {
            $res = $this->taskRepository->create($data);
            if ($res) {
                Log::info(CREATE_SUCCEED . " " . self::MODEL, ['data' => $data]);
            }
        } catch (QueryException $e) {
            Log::error(message: ERROR_DATABASE . " " . self::MODEL . " : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_CREATE_FAILED . " " . self::MODEL . " : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function updateTask($id, array $data)
    {
        try {
            $res = $this->taskRepository->update($id, $data);
            if ($res) {
                Log::info(UPDATE_SUCCEED . " " . self::MODEL . " has id {$id}");
            }
            return $res;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " " . self::MODEL . " : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_UPDATE_FAILED . " " . self::MODEL . " with id {$id} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function deleteTask($id)
    {
        try {
            $res = $this->taskRepository->delete($id);
            if ($res) {
                Log::info(DELETE_SUCCEED . " " . self::MODEL . "  has id {$id}");
            }
            return $res;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " " . self::MODEL . "  : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_DELETE_FAILED . " " . self::MODEL . "  with id {$id} : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function searchTask(Request $request)
    {
        $projects = Project::all();
        $data = $request->only(['name', 'project_id']);
        $sortBy = $request->input('sort_by', 'id');
        $order = $request->input('order', 'desc');

        try {
            if ($request->has('search')) {
                $tasks = $this->taskRepository->searchPagingAndSort($data, $sortBy, $order)->appends([
                    'name' => $request->input('name'),
                    'project_id' => $request->input('project_id'),
                    'sort_by' => $sortBy,
                    'order' => $order,
                ]);
            } else {
                $tasks = $this->getAllTasks();
            }

            return [
                'tasks' => $tasks,
                'projects' => $projects ?: collect(),
                'data' => $data,
                'sortBy' => $sortBy,
                'order' => $order,
            ];
        } catch (Throwable $e) {
            Log::error(ERROR_SEARCH  . ' $e->getMessage()');
        }
    }
}
