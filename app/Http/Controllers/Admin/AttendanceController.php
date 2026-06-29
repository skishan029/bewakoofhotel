<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $count = count($request->employee_id);

            for ($i = 0; $i < $count; $i++) {
                $validator = validator([
                    'employee_id' => $request->employee_id[$i],
                    'atten_date'  => $request->atten_date[$i],
                    'status'      => $request->status[$i],
                    'amount'      => $request->amount[$i] ?? null,
                ], [
                    'employee_id' => 'required|integer|exists:employees,id',
                    'atten_date'  => 'required|date',
                    'status'      => 'required|in:1,2,3',
                    'amount'      => 'nullable|numeric',
                ]);

                if ($validator->fails()) {
                    return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
                }

                Attendance::updateOrCreate(
                    [
                        'employee_id' => $request->employee_id[$i],
                        'atten_date'  => $request->atten_date[$i],
                    ],
                    [
                        'status' => $request->status[$i],
                        'amount' => $request->amount[$i] ?? null
                    ]
                );
            }

            return response()->json(['type' => 'success', 'message' => 'Attendance saved successfully']);
        }

        return view('admin.attendance.empatten', [
            'employee'   => Employee::all(),
            'title'      => 'Attendance',
            'submitURL'  => route('admin.attendance.create'),
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $data = Attendance::whereHas('employee', function ($q) {
            //     $q->whereNull('deleted_at'); // Only include non-deleted employees
            // })->with('employee');

            $data = Attendance::with('employee')
            ->whereHas('employee', fn($q) => $q->whereNull('deleted_at'))
            ->get();
            
            if (Auth::guard('admin')->user()->user_type != '1') {
                $data->whereDate('atten_date', Carbon::today());
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_id', fn($row) => $row->employee->emp_name ?? '-')
                ->editColumn('atten_date', fn($row) => $row->atten_date)
                ->editColumn('status', fn($row) => match ($row->status) {
                    '1' => 'Present', '2' => 'Absent', '3' => 'Half Day', default => '-'
                })
                ->editColumn('amount', fn($row) => $row->amount)
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group">';

                    $url = route('admin.attendance.edit', ['id'=> $row->id]);
                    $btn .= \App\Helper\Helper::commonEditButton($url);

                    $btn .= "<button class='btn btn-sm btn-danger btn-flat' value='{$row->id}' type='button' title='Delete' onclick='deleteAttendance(this)'><i class='fas fa-trash-alt'></i></button>";

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.attendance.index', [
            'table' => collect([
                'employee_id' => 'Employee Name',
                'atten_date'  => 'Date',
                'status'      => 'Status',
                'amount'      => 'Amount',
                'action'      => 'Action',
            ]),
            'dataTableURL' => route('admin.attendance.index'),
            'title'        => 'All Attendance',
        ]);
    }

    public function edit(Request $request, $id = null)
    {
        $attendance = Attendance::findOrFail($id);
        if ($request->ajax()) {
            // If we're updating multiple employees at once (bulk edit)
            if (is_array($request->employee_id)) {
                $count = count($request->employee_id);

                for ($i = 0; $i < $count; $i++) {
                    $validator = validator([
                        'employee_id' => $request->employee_id[$i],
                        'atten_date'  => $request->atten_date[$i],
                        'status'      => $request->status[$i],
                        'amount'      => $request->amount[$i] ?? null,
                    ], [
                        'employee_id' => 'required|integer|exists:employees,id',
                        'atten_date'  => 'required|date',
                        'status'      => 'required|in:1,2,3',
                        'amount'      => 'nullable|numeric',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
                    }

                    Attendance::updateOrCreate(
                        [
                            'employee_id' => $request->employee_id[$i],
                            'atten_date'  => $request->atten_date[$i],
                        ],
                        [
                            'status' => $request->status[$i],
                            'amount' => $request->amount[$i] ?? null
                        ]
                    );
                }

                return response()->json(['type' => 'success', 'message' => 'Attendance updated successfully']);
            }

            // Single record edit (fallback)
            //$attendance = Attendance::findOrFail($id);

            $validator = validator($request->all(), [
                'employee_id' => 'required|integer|exists:employees,id',
                'atten_date'  => 'required|date',
                'status'      => 'required|in:1,2,3',
                'amount'      => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            $attendance->employee_id = $request->employee_id;
            $attendance->atten_date  = $request->atten_date;
            $attendance->status      = $request->status;
            $attendance->amount      = $request->amount ?? null;
            $attendance->save();

            return response()->json(['type' => 'success', 'message' => 'Attendance updated successfully']);
        }

        // Non-AJAX request -> show edit page
        return view('admin.attendance.empatten', [
            'attendance' => Attendance::find($id),
            'employee'   => Employee::all(),
            'title'      => 'Edit Attendance',
            'submitURL'  => route('admin.attendance.edit', ['id'=> $id]),
        ]);
    }

    function delete(Request $request) 
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'id'=> 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }
            $employeeAttentance = Attendance::find($request->id);
            if (blank($employeeAttentance)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }
            $employeeAttentance->delete();

            return response()->json(['type'=>'success', 'message'=> "Successfully deleted"]);
        }
    }

    public function checkAttendance(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'employee_id' => 'required|integer|exists:employees,id',
                'atten_date'  => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            $date = $request->atten_date ?: now()->format('Y-m-d');

            $attendance = Attendance::where('employee_id', $request->employee_id)
                ->where('atten_date', $date)
                ->first();

            return response()->json([
                'type' => 'success',
                'attendance' => $attendance
            ]);
        }
    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            $query = Attendance::whereHas('employee', function ($q) {
                $q->whereNull('deleted_at'); // Only include non-deleted employees
            })->with('employee');

            if ($request->filled(['start_date', 'end_date'])) {
                $query->whereBetween('atten_date', [
                    date('Y-m-d', strtotime($request->start_date)),
                    date('Y-m-d', strtotime($request->end_date)),
                ]);
            }

            if (!empty($request->employee_id)) {
                $query->where('employee_id', $request->employee_id);
            }

            if (!empty($request->status)) { // ✅ Filter by status
                $query->where('status', $request->status);
            }

            return DataTables::of($query->latest()->get())
                ->addIndexColumn()
                ->editColumn('atten_date', fn($row) => date('d-m-Y', strtotime($row->atten_date)))
                ->addColumn('emp_name', fn($row) => $row->employee->emp_name ?? '-')
                ->editColumn('status', fn($row) => match ($row->status) {
                    '1' => 'Present', '2' => 'Absent', '3' => 'Half Day', default => '-'
                })
                ->editColumn('amount', fn($row) => number_format($row->amount, 2, '.', ''))
                ->make(true);
        }

        return view('admin.report.attendance', [
            'title'        => 'Attendance Report',
            'employee'     => Employee::orderBy('emp_name')->get(),
            'dataTableURL' => route('admin.report.attendance'),
            'table'        => collect([
                'atten_date' => 'Date',
                'emp_name'   => 'Employee',
                'status'     => 'Status',
                'amount'     => 'Amount',
            ]),
        ]);
    }
}