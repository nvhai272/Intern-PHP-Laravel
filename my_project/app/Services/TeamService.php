<?php

namespace App\Services;

use App\Repositories\TeamRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class TeamService
{
    protected TeamRepository $teamRepository;
    private string $table;

    //private const TABLE = 'Team';

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->table = $this->teamRepository->getTableName();
    }

    /**
     * @throws Exception
     */
    public function getTeamById($id)
    {
        if (!is_numeric($id)) {
            Log::info(ERROR_ID . " {$this->table} has id {$id}");
            throw new InvalidArgumentException(ERROR_ID);
        }
        try {
            $team = $this->teamRepository->getById($id);
            if ($team === null) {
                Log::error(ERROR_NOT_FOUND . " {$this->table} has id {$id}");
                return null;
            }
            return $team;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " {$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . " {$this->table} has id {$id} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function getAllTeams($sortBy = 'id', $order = 'desc')
    {
        try {
//            return $this->teamRepository->getAll();
            return $this->teamRepository->getAllPagingAndSort($sortBy, $order);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_READ_FAILED . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function createTeam(array $data)
    {
        try {
//            dd($data);
            $res = $this->teamRepository->create($data);
            // thành công trả về đối tượng, thất bại ném exeption
            if ($res) {
                Log::info(CREATE_SUCCESSED . " {$this->table}", ['data' => $data]);
            }
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . " {$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_CREATE_FAILED . " {$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function updateTeam($id, array $data)
    {
        try {
            $res = $this->teamRepository->update($id, $data);
            if ($res) {
                Log::info(UPDATE_SUCCESSED . " {$this->table} has id {$id}");
            }
            return $res;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_UPDATE_FAILED . "{$this->table} with id {$id} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function deleteTeam($id)
    {
        try {
            $res = $this->teamRepository->delete($id);
            if ($res) {
                Log::info(DELETE_SUCCESSED . " {$this->table} has id {$id}");
            }
            return $res;
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error(ERROR_DELETE_FAILED . "{$this->table} with id {$id} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }

    public function searchTeam($data, $sortBy, $order)
    {
        try {
            return $this->teamRepository->searchPagingAndSort($data, $sortBy, $order);
        } catch (QueryException $e) {
            Log::error(ERROR_DATABASE . "{$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        } catch (Throwable $e) {
            Log::error("Error in {$this->table} : " . $e->getMessage());
            throw new RuntimeException(ERROR_SYSTEM);
        }
    }
}
