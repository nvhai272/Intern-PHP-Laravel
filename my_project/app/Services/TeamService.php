<?php

namespace App\Services;

use App\Repositories\TeamRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class TeamService
{
    protected TeamRepository $teamRepository;
    private string $table;

//    private const TABLE = 'Team';

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->table = $this->teamRepository->getTableName();
    }

    /**
     * @throws Exception
     */
    public function getTeamById($id): Model
    {
        if (!is_numeric($id)) {
            Log::info(ERROR_ID . " {$this->table} with id {$id}");
            throw new InvalidArgumentException(ERROR_ID);
        }
        try {
            $team = $this->teamRepository->getById($id);
            if (!$team) {
                Log::info(ERROR_NOT_FOUND . " {$this->table} with id {$id}");
                throw new RuntimeException(ERROR_NOT_FOUND);
            }
            return $team;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " {$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . " {$this->table} with id {$id} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function getAllTeam(): Collection
    {
        try {
            $teams = $this->teamRepository->getAll();
            if ($teams->isEmpty()) {

            }
            return $teams;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function getAllTeamWithPagination($amount): LengthAwarePaginator
    {
        try {
            return $this->teamRepository->getAllPaging($amount);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function createTeam(array $data): Model
    {
        try {
            return $this->teamRepository->create($data);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_CREATE_FAILED . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function updateTeam($id, array $data): bool
    {
        try {
            return $this->teamRepository->update($id, $data);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_UPDATE_FAILED . "{$this->table} with id {$id} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function deleteTeam($id): bool
    {
        try {
            return $this->teamRepository->delete($id);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_DELETE_FAILED . "{$this->table} with id {$id} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function searchTeam($amount, array $filters, $sort = null, $direction = 'asc')
    {
        try {
            return $this->teamRepository->searchPaging($amount, $filters, $sort, $direction);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_SEARCH_FAILED . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }
}
