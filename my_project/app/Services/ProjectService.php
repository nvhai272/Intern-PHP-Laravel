<?php

namespace App\Services;

use App\Repositories\Interfaces\IProjectRepository;
use App\Repositories\TeamRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class ProjectService
{
    protected $projectRepository;
    protected const MODEL = 'Project';
    protected const TEAM_PROJECT = 'Team of Project';

    public function __construct(IProjectRepository $proRepo)
    {
        $this->projectRepository = $proRepo;
    }

    public function getProJectById($id)
    {
        if (!is_numeric($id)) {
            Log::info(ERROR_ID . " " . self::MODEL . " has id {$id}");
            throw new InvalidArgumentException(ERROR_ID);
        }
        try {
            $pro = $this->projectRepository->getById($id);
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

    public function getAllProjects($sortBy = 'id', $order = 'desc')
    {
        try {
            return $this->projectRepository->getAllPagingAndSort($sortBy, $order);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " " . self::MODEL . "  : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . " " . self::MODEL . "  : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function createProject(array $data)
    {
        try {
            $res = $this->projectRepository->create($data);
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

    public function updateProject($id, array $data)
    {
        try {
            $res = $this->projectRepository->update($id, $data);
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

    public function deleteProject($id)
    {
        try {
            $res = $this->projectRepository->delete($id);
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

    public function searchProject($data, $sortBy, $order)
    {
        try {
            return $this->projectRepository->searchPagingAndSort($data, $sortBy, $order);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " " . self::MODEL . "  : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error("Error in "  . self::MODEL . "  : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

    public function teamOfProject($projectId, $sortBy='id', $order='asc'){
        try {
            return $this->projectRepository->listTeamOfProject($projectId, $sortBy, $order);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " " . self::TEAM_PROJECT . "  : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error("Error in "  . self::TEAM_PROJECT . "  : " . $e->getMessage());
            throw new Exception(ERROR_SYSTEM);
        }
    }

}
