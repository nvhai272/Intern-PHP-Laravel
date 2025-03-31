<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\TeamService;
use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class TeamController extends Controller
{
    protected TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    // show all

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index()
    {
        $sortBy = request()->get('sort_by', 'id');
        $order = request()->get('order', 'desc');

        // lấy từ request -> thằng này từ đâu? -> laasy từ URL

        $teams = $this->teamService->getAllTeams($sortBy, $order)
            // không cần
//            ->appends([
//            'sort_by' => $sortBy,
//            'order' => $order
//        ])
        ;
        return view('team.index', compact('teams', 'sortBy', 'order'));
    }

    // show detail
    public function show($id)
    {
        try {
            $team = $this->teamService->getTeamById($id);

            if ($team === null) {
                return view('layouts.err', ['msgErr' => ERROR_NOT_FOUND]);
            }

            return view('team.show', compact('team'));
        } catch (Throwable $e) {
            return redirect('/management/team/list')->with('msgErr', $e->getMessage());
        }
    }

    // create form
    public function showCreateForm()
    {
        $validatedData = session('dataCreateTeam', []);
        return view('team.create', compact('validatedData'));
    }

    // show confirm
    public function confirmCreate(TeamCreateRequest $request)
    {
        $validated = $request->validated();
        session(['dataCreateTeam' => $validated]);
        return view('team.add-confirm', compact('validated'));
    }

    // create
    public function create(Request $request)
    {
        try {
            $this->teamService->createTeam($request->except('_token'));
            session()->forget('dataCreateTeam');

            return redirect('/management/team/list')->with('msg', CREATE_SUCCESSED);
        } catch (Throwable $e) {
            return view('team.create')->with('msgErr', $e->getMessage());
        }
    }

    public function showEditForm($id)
    {
        //  có thể lấy giá trị id bằng đối tượng request, thông qua router hoặc lấy thằng từ id chuyền vào
        //  $id = $request->route('id');
        try {
            $team = $this->teamService->getTeamById($id);
            if ($team === null) {
                return view('layouts.err', ['msgErr' => ERROR_NOT_FOUND]);
            }
            return view('team.edit', compact('team'));
        } catch (\Exception $e) {
            return redirect("/management/team/list")->with('msgErr', $e->getMessage());
        }
    }

    // show confirm
    public function confirmEdit(TeamUpdateRequest $request)
    {
        $id = $request->input('id');
        $validated = $request->validated();
//        session(['dataEditTeam' => $validated]);
        return view('team.edit-confirm', compact('validated', 'id'));
    }

    // create
    public function edit(Request $request)
    {
        try {
            $id = $request->route('id');
            $this->teamService->updateTeam($id, $request->except('_token'));
//            session()->forget('dataEditTeam');
            return redirect('/management/team/list')->with('msg', UPDATE_SUCCESSED);
        } catch (Throwable $e) {
            return redirect('/management/team/list')->with('msgErr', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->teamService->deleteTeam($id);
            return redirect('/management/team/list')->with('msg', DELETE_SUCCESSED);
        } catch (Throwable $e) {
            return redirect('/management/team/list')->with('msg', $e->getMessage());
        }
    }
}


