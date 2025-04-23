<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Throw_;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Throwable;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        // sleep(6);
        $sortBy = request()->get('sort_by', 'id');
        $order = request()->get('order', 'desc');

        $tasks = $this->taskService->getAllTasks($sortBy, $order)
            ->appends([
                'sort_by' => $sortBy,
                'order' => $order
            ]);
        return view('task.index', compact('tasks', 'sortBy', 'order'));
    }

    public function show($id)
    {
        try {
            $task = $this->taskService->getTaskById($id);

            if ($task === null) {
                return view('layouts.err', ['msgErr' => ERROR_NOT_FOUND]);
            }

            $employees = $task->employees()
                ->orderBy('id', 'desc')
                ->paginate(5, ['*'], 'employees_page');

            return view('task.show', compact('task', 'employees'));
        } catch (Throwable $e) {
            return redirect('/management/task/list')->with('msgErr', $e->getMessage());
        }
    }

    public function showCreateForm()
    {
        $projects = Project::all();
        $validatedData = session('dataCreateTask', []);

        return view('task.create', compact('validatedData', 'projects'));
    }

    public function confirmCreate(TaskCreateRequest $request)
    {
        $validated = $request->validated();
        session(['dataCreateTask' => $validated]);
        $task = new Task($validated);
        // dd($task);
        return view('task.add-confirm', compact('task'));
    }

    public function create(Request $request)
    {
        try {
            $this->taskService->createTask($request->except('_token'));
            session()->forget('dataCreateTask');

            return redirect('/management/task/list')->with('msg', CREATE_SUCCEED);
        } catch (Throwable $e) {
            return view('task.create')->with('msgErr', $e->getMessage());
        }
    }

    public function showEditForm($id)
    {
        //  có thể lấy giá trị id bằng đối tượng request, thông qua router hoặc lấy thằng từ id chuyền vào
        //  $id = $request->route('id');

        $projects = Project::all();
        try {
            $task = $this->taskService->getTaskById($id);
            if ($task === null) {
                return view('layouts.err', ['msgErr' => ERROR_NOT_FOUND]);
            }
            return view('task.edit', compact('task', 'projects'));
        } catch (Throwable $e) {
            return redirect("/management/task/list")->with('msgErr', $e->getMessage());
        }
    }

    public function confirmEdit(TaskUpdateRequest $request)
    {
        $id = $request->input('id');
        $validated = $request->validated();
        $model = new Task($validated);
        session(['dataEditTask' => $validated]);
        return view('task.edit-confirm', compact('model', 'id'));
    }

    public function edit(Request $request)
    {
        try {
            $id = $request->route('id');
            $this->taskService->updateTask($id, $request->except('_token'));
            session()->forget('dataEditTask');
            return redirect('/management/task/list')->with('msg', UPDATE_SUCCEED);
        } catch (Throwable $e) {
            return redirect('/management/task/list')->with('msgErr', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->taskService->deleteTask($id);
            return redirect()->route('task.list')->with('msg', DELETE_SUCCEED);
        } catch (Throwable $e) {
            //
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }


    public function search(Request $request)
    {
        $data = $this->taskService->searchTask($request);
        return view(
            'task.search',
            $data

            //     ['teams'=>$data['teams']?? collect(),
            //     'emps'=>$data['emps']?? collect(),
            //     'data'=>$data['data']?? collect(),
            //     'sortBy'=>$data['sortBy']?? 'id',
            //     'order'=>$data['order']?? 'desc',
            // ]
        );
    }
}
