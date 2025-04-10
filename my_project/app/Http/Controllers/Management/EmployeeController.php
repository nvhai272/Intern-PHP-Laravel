<?php

namespace App\Http\Controllers\Management;

use App\Exports\EmployeeExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Models\Team;
use App\Services\EmployeeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;
use Throwable;

class EmployeeController extends Controller
{
    protected EmployeeService $empService;

    public function __construct(EmployeeService $empService)
    {
        $this->empService = $empService;
    }

    public function index()
    {
        $sortBy = request()->get('sort_by', 'id');
        $order = request()->get('order', 'desc');

        $employees = $this->empService->getAllEmployees($sortBy, $order)
            ->appends([
                'sort_by' => $sortBy,
                'order' => $order
            ]);
        return view('employee.index', compact('employees', 'sortBy', 'order'));
    }

    public function show($id)
    {
        try {
            $emp = $this->empService->getEmployeeById($id);

            if ($emp === null) {
                return view('layouts.err', ['msgErr' => ERROR_NOT_FOUND]);
            }

            return view('employee.show', compact('emp'));
        } catch (Throwable $e) {
            return redirect('/management/employee/list')->with('msgErr', $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $data = $this->empService->searchEmployee($request);
        return view(
            'employee.search',
            $data

            //     ['teams'=>$data['teams']?? collect(),
            //     'emps'=>$data['emps']?? collect(),
            //     'data'=>$data['data']?? collect(),
            //     'sortBy'=>$data['sortBy']?? 'id',
            //     'order'=>$data['order']?? 'desc',
            // ]
        );
    }

    public function exportCSV()
    {
        // dd(request()->all());
        // không truyền Request vào đây thì cần truyền pram vào khi gọi đến route GET để trên url có giá trị request
        $dataSearchExportFile = [
            'team_id' => request('team_id'),
            'full_name' => request('full_name'),
            'email' => request('email'),
        ];

        $nameFile = 'Employees';
        if (!empty($dataSearchExportFile['team_id'])) {
            $nameFile = 'Employees Team ' . Team::find($dataSearchExportFile['team_id'])->name;
        }

        $sortBy = request('sort_by', 'id');
        $order = request('order', 'asc');

        $data = $this->empService->getDataExportFileCSV($dataSearchExportFile, $sortBy, $order);

        if ($data->isEmpty()) {
            return redirect()->route('emp.search', request()->all())->with('msgErr', ERR_DATA_EXPORT);
        }
        return Excel::download(new EmployeeExport($data), $nameFile . '.csv');
    }

    public function showCreateForm()
    {
        $teams = Team::all();

        //    dd($request);
        // dd(session('dataCreateEmp'));

        $oldData = session('dataCreateEmp');
        return view('employee.create', compact('teams', 'oldData'));
    }

    public function confirmCreate(EmployeeCreateRequest $request)
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        unset($validated['avatar_upload']);

        session(['dataCreateEmp' => $validated]);

        $model = new Employee($validated);
        return view('employee.add-confirm', compact('model'));
    }

    public function create(Request $request)
    {
        try {
            // dd($request->all());
            $this->empService->createEmp($request->except('_token'));
            return redirect()->route('emp.list')->with('msg', CREATE_SUCCEED);
        } catch (Throwable $e) {
            // dd($e->getMessage());
            return view('employee.create', [
                'teams' => Team::all(),
                'oldData' => session('dataCreateEmp'),
                'msgErr' => $e->getMessage()
            ]);
        }
    }

    public function showEditForm($id)
    {
        //  có thể lấy giá trị id bằng đối tượng request, thông qua router hoặc lấy thằng từ id chuyền vào
        //  $id = $request->route('id');
        try {
            $emp = $this->empService->getEmployeeById($id);
            if ($emp === null) {
                return view('layouts.err', ['msgErr' => ERROR_NOT_FOUND]);
            }

            // dd($emp);

            $oldData = session('dataEditEmp');
            $teams = Team::all();
            return view('employee.edit', compact('teams', 'emp','oldData'));
        } catch (Throwable $e) {
            return redirect("/management/employee/list")->with('msgErr', $e->getMessage());
        }
    }

    public function confirmEdit(EmployeeUpdateRequest $request)
    {
        //   dd($request->all());

        $id = $request->input('id');
        $validated = $request->validated();
        unset($validated['avatar_upload']);
        // thêm field avatar -> lưu session để khi quay lại còn ảnh cũ
        // dùng validated có ảnh mới hoặc ảnh cũ rồi
        // dd($validated);
        session(['dataEditEmp' => $validated]);
        $model = new Employee($validated);
        // dd($model);
        return view('employee.edit-confirm', compact('model', 'id'));
    }

    public function edit(Request $request)
    {
        try {
            $id = $request->route('id');
            $this->empService->updateEmp($id, $request->except('_token'));
            return redirect('/management/employee/list')->with('msg', UPDATE_SUCCEED);
        } catch (Throwable $e) {
            return redirect('/management/employee/list')->with('msgErr', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->empService->deleteEmp($id);
            return redirect()->route('emp.list')->with('msg', DELETE_SUCCEED);
        } catch (Throwable $e) {
            //
            return view('layouts.err', ['msgErr' => $e->getMessage()]);
        }
    }
}
