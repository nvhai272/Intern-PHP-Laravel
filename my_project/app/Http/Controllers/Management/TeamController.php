<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\TeamService;
use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;
use Throwable;

class TeamController extends Controller
{
    protected TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    // show all
    public function index()
    {
        $teams = $this->teamService->getAllTeams();
        return view('teams.index', compact('teams'));
    }

    // show detail
    public function show($id)
    {
        try {
            $team = $this->teamService->getTeamById($id);
            return view('team.show', compact('team'));
        } catch (Throwable $e) {
            return redirect()->route('team.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    // create form
    public function showCreateForm(){
        return view('teams.create');
    }

    // create
    public function create(TeamCreateRequest $request){
      Team::create($request->validate());
    }


}


