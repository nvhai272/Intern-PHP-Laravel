<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\TeamService;
use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Throw_;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Throwable;

class TeamController extends Controller
{
    protected TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index()
    {
        sleep(6);
        $sortBy = request()->get('sort_by', 'id');
        $order = request()->get('order', 'desc');

        $teams = $this->teamService->getAllTeams($sortBy, $order)
            ->appends([
                'sort_by' => $sortBy,
                'order' => $order
            ]);
        return view('team.index', compact('teams', 'sortBy', 'order'));
    }

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

    public function showCreateForm()
    {
        $validatedData = session('dataCreateTeam', []);
        return view('team.create', compact('validatedData'));
    }

    public function confirmCreate(TeamCreateRequest $request)
    {
        $validated = $request->validated();
        session(['dataCreateTeam' => $validated]);
        return view('team.add-confirm', compact('validated'));
    }

    public function create(Request $request)
    {
        try {
            $this->teamService->createTeam($request->except('_token'));
            session()->forget('dataCreateTeam');

            return redirect('/management/team/list')->with('msg', CREATE_SUCCEED);
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
        } catch (Throwable $e) {
            return redirect("/management/team/list")->with('msgErr', $e->getMessage());
        }
    }

    public function confirmEdit(TeamUpdateRequest $request)
    {
        $id = $request->input('id');
        $validated = $request->validated();
        //        session(['dataEditTeam' => $validated]);
        return view('team.edit-confirm', compact('validated', 'id'));
    }

    public function edit(Request $request)
    {
        try {
            $id = $request->route('id');
            $this->teamService->updateTeam($id, $request->except('_token'));
            //            session()->forget('dataEditTeam');
            return redirect('/management/team/list')->with('msg', UPDATE_SUCCEED);
        } catch (Throwable $e) {
            return redirect('/management/team/list')->with('msgErr', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->teamService->deleteTeam($id);
            return redirect()->route('team.list')->with('msg', DELETE_SUCCEED);
        } catch (Throwable $e) {
            //
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }


    public function search(Request $request)
    {
        $data = $request->only(['name']);
        $sortBy = $request->input('sort_by', 'id');
        $order = $request->input('order', 'desc');

        $teams=$this->teamService->getAllTeams($sortBy,$order);

        if ($request->input('search') && !$request->filled('name')) {
            return redirect()->route('team.search')
                //                ->withErrors(['msgErr' => ERR_INPUT_SEARCH])
                ->with(['msgErr' => ERR_INPUT_SEARCH])
                ->withInput();
        }

        if ($request->input('search') && $request->filled('name')) {
            $teams = $this->teamService->searchTeam($data, $sortBy, $order);
        }

        return view('team.search', compact('teams', 'sortBy', 'order'));
    }
}
