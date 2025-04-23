<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTaskRequest;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Services\ProjectService;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $sortBy = request()->get('sort_by', 'id');
        $order = request()->get('order', 'desc');

        $projects = $this->projectService->getAllProjects($sortBy, $order)
            ->appends([
                'sort_by' => $sortBy,
                'order' => $order
            ]);
        return view('project.index', compact('projects', 'sortBy', 'order'));
    }

    public function show($id)
    {
        try {
            $pro = $this->projectService->getProjectById($id);

            if ($pro === null) {
                return view('layouts.err', ['msgErr' => ERROR_NOT_FOUND]);
            }

            $teams = $pro->teams()->orderBy('id')->paginate(3, ['*'], 'teams_page');
            // lấy danh sách id của team mà có trong bảng trung gian với project
            // $teamIds = $teams->pluck('id');

            $employees = $pro->employees()->orderBy('id')->paginate(3, ['*'], 'employees_page');
            // $empIds = $employees->pluck('id');


            $taskQuery = $pro->tasks();
            $sortBy = request('sort_by');
            $order = request('order');

            if ($sortBy && in_array($order, ['asc', 'desc'])) {
                $taskQuery->orderBy($sortBy, $order);
            } else {
                $taskQuery->orderByDesc('id');
            }

            $tasks = $taskQuery->paginate(10, ['*'], 'tasks_page');

            return view('project.show', compact('pro', 'teams', 'employees', 'tasks'));
        } catch (Throwable $e) {
            return redirect('/management/project/list')->with('msgErr', $e->getMessage());
        }
    }

    public function taskListOfProject(Project $project)
    {
        // 1 thằng lấy route param, 1 thằng lấy request param => hoặc lấy bằng biến truyền vào controller bên trên
        // dd(request()->route('project'));
        // dd(request()->all());
        // $tasks = $pro->tasks()->orderByDesc('id')->paginate(10, ['*'], 'tasks_page');
    }

    public function showCreateForm()
    {
        $validatedData = session('dataCreateProject', []);
        return view('project.create', compact('validatedData'));
    }


    public function confirmCreate(ProjectCreateRequest $request)
    {
        $validated = $request->validated();
        session(['dataCreateProject' => $validated]);
        return view('project.add-confirm', compact('validated'));
    }

    public function create(Request $request)
    {
        try {
            $this->projectService->createProject($request->except('_token'));
            session()->forget('dataCreateProject');

            return redirect('/management/project/list')->with('msg', CREATE_SUCCEED);
        } catch (Throwable $e) {
            return view('project.create')->with('msgErr', $e->getMessage());
        }
    }

    public function showEditForm($id)
    {
        //  $id = $request->route('id');
        try {
            $pro = $this->projectService->getProJectById($id);
            if ($pro === null) {
                return view('layouts.err', ['msgErr' => ERROR_NOT_FOUND]);
            }
            $oldData = session('dataEditProject');
            return view('project.edit', compact('pro', 'oldData'));
        } catch (Throwable $e) {
            return redirect("/management/project/list")->with('msgErr', $e->getMessage());
        }
    }

    public function confirmEdit(ProjectUpdateRequest $request)
    {
        $id = $request->input('id');
        $validated = $request->validated();
        session(['dataEditProject' => $validated]);
        return view('project.edit-confirm', compact('validated', 'id'));
    }

    public function edit(Request $request)
    {
        try {
            $id = $request->route('id');
            $this->projectService->updateProject($id, $request->except('_token'));
            session()->forget('dataEditTeam');
            return redirect('/management/project/list')->with('msg', UPDATE_SUCCEED);
        } catch (Throwable $e) {
            return redirect('/management/project/list')->with('msgErr', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->projectService->deleteProject($id);
            return redirect()->route('project.list')->with('msg', DELETE_SUCCEED);
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

        $projects = $this->projectService->getAllProjects($sortBy, $order);

        if ($request->input('search') && !$request->filled('name')) {
            return redirect()->route('project.search')
                //                ->withErrors(['msgErr' => ERR_INPUT_SEARCH])
                ->with(['msgErr' => ERR_INPUT_SEARCH])
                ->withInput();
        }

        if ($request->input('search') && $request->filled('name')) {
            $projects = $this->projectService->searchProject($data, $sortBy, $order);
        }

        return view('project.search', compact('projects', 'sortBy', 'order'));
    }

    public function addTeam(Project $project)
    {
        try {

            $id = $project->id;
            $listTeam = $project->unassignedTeams()->orderBy('id')->paginate(3);

            return view('project.add-team', compact('listTeam', 'project'));
        } catch (Throwable $e) {
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }

    public function confirmAddTeam(Request $request, Project $project)
    {
        try {
            $request->validate([
                'teams' => 'required|array|min:1',
                'teams.*' => 'exists:m_teams,id',
            ]);
            $project->teams()->attach($request->teams);
            return redirect()->route('project.detail', $project->id)->with('msg', 'Add Team To Project');
        } catch (Throwable $e) {
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }

    public function confirmDeleteTeam(Request $request, Project $project)
    {
        dd(request()->all());
        try {
            $request->validate([
                'teams' => 'required|array|min:1',
                'teams.*' => 'exists:m_teams,id',
            ]);
            $project->teams()->detach($request->teams);
            return redirect()->back()
                // ->with()
            ;
        } catch (Throwable $e) {
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }

    public function confirmDeleteEmployee(Request $request, Project $project)
    {
        try {
            $request->validate([
                'employees' => 'required|array|min:1',
                'employees.*' => 'exists:m_employees,id',
            ]);
            $project->employees()->detach($request->employees);
            return redirect()->back()
                // ->with()
            ;
        } catch (Throwable $e) {
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }

    public function addEmployee(Project $project)
    {
        try {
            $id = $project->id;
            $listEmp = $project->unassignedEmps()->get();
            $data = ['project' => $project, 'listEmp' => $listEmp];
            // $token = uniqid();
            // cache()->put($token, $data, 300);

            return view('project.add-emp', compact('project', 'data'));
        } catch (Throwable $e) {
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }

    public function confirmAddEmployee(Request $request, Project $project)
{
    try {
        $request->validate([
            'employees' => 'required|array|min:1',
            'employees.*' => 'exists:m_employees,id',
        ]);

        $validEmployees = [];

        foreach ($request->employees as $employeeId) {
            $exists = DB::table('employee_project')
                ->where('project_id', $project->id)
                ->where('employee_id', $employeeId)
                ->where('del_flag', 0)
                ->exists();

            if (!$exists) {
                $validEmployees[] = $employeeId;
            }
        }

        if (!empty($validEmployees)) {
            $project->employees()->attach($validEmployees);
        }

        return redirect()->route('project.detail', $project->id)
            ->with('msg', 'Add Emp To Project');
    } catch (Throwable $e) {
        return view('layouts.err', ['msgErr' => $e->getMessage()]);
    }
}


    public function addTask(Project $project)
    {
        try {
            $id = $project->id;

            // $project = Project::with(['employees.team'])->find($id);
            // $emps = $project->employees;

            $emps = $project->assignedEmps()->get();
            // dùng cách này ok hơn, có thể thêm filter cột bảng trung gian

            return view('project.add-task', compact('project', 'emps'));
        } catch (Throwable $e) {
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }

    public function confirmAddTask(AddTaskRequest $request, Project $project)
{
    DB::beginTransaction();
    try {
        $task = Task::create([
            'name' => $request->name,
            'task_status' => $request->task_status,
            'project_id' => $project->id,
        ]);

        $validEmployees = [];

        foreach ($request->employees as $empId) {
            $exists = DB::table('employee_task')
                ->where('task_id', $task->id)
                ->where('employee_id', $empId)
                ->where('del_flag', 0)
                ->exists();

            if (!$exists) {
                $validEmployees[] = $empId;
            }
        }

        if (!empty($validEmployees)) {
            $task->employees()->attach($validEmployees);
        }

        DB::commit();

        return redirect()->route('project.detail', $project->id)
            ->with('msg', 'Add Task To Project');
    } catch (Throwable $e) {
        DB::rollBack();
        return view('layouts.err', ['msgErr' => $e->getMessage()]);
    }
}



}
