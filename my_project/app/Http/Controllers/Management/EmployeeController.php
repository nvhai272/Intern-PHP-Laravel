<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeCreateRequest;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
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
       ])
        ;
        return view('employee.index', compact('employees', 'sortBy', 'order'));
    }

    // show detail
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

    // create form
    public function showCreateForm()
    {
        $validatedData = session('dataCreateEmp', []);
        return view('employee.create', compact('validatedData'));
    }

    // show confirm
    public function confirmCreate(EmployeeCreateRequest $request)
    {
        $validated = $request->validated();
        session(['dataCreateTeam' => $validated]);
        return view('team.add-confirm', compact('validated'));
    }



}
