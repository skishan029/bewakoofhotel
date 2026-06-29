<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalaryReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('employees as e')
            ->leftJoin('attendances as a', function ($join) {
                $join->on('e.id', '=', 'a.employee_id');
                $join->whereNull('a.deleted_at'); // ✅ exclude deleted attendance
            })
            ->select(
                'e.emp_name as employee',
                DB::raw("DATE_FORMAT(a.atten_date, '%b %Y') as month"),
                'e.salary',
                DB::raw("
                    (COUNT(CASE WHEN a.status = '1' THEN 1 END) + 
                    (COUNT(CASE WHEN a.status = '3' THEN 1 END) / 2)) as days_present
                "),
                DB::raw("SUM(IFNULL(a.amount,0)) as amount_taken"),
                DB::raw("
                    (
                        (COUNT(CASE WHEN a.status = '1' THEN 1 END) + 
                        (COUNT(CASE WHEN a.status = '3' THEN 1 END) / 2)) * e.salary
                    ) - IFNULL(SUM(a.amount), 0) as due_salary
                ")
            )
            ->whereNull('e.deleted_at');

            // Month filter
            if ($request->filled('month')) {
                $month = date('m', strtotime($request->month));
                $year = date('Y', strtotime($request->month));
                $query->whereMonth('a.atten_date', $month)
                      ->whereYear('a.atten_date', $year);
            }

            // Employee filter
            if (!empty($request->employee_id)) {
                $query->where('e.id', $request->employee_id);
            }

            $query->groupBy(
                'e.id',
                DB::raw("DATE_FORMAT(a.atten_date, '%b %Y')"),
                'e.salary',
                'e.emp_name'
            )->orderBy('e.emp_name');

            return DataTables::of($query->get())
                ->addIndexColumn()
                ->editColumn('salary', fn($row) => number_format($row->salary, 2, '.', ''))
                ->editColumn('days_present', fn($row) => number_format($row->days_present, 1))
                ->editColumn('amount_taken', fn($row) => number_format($row->amount_taken, 2, '.', ''))
                ->editColumn('due_salary', fn($row) => number_format($row->due_salary, 2, '.', ''))
                ->make(true);
        }

        return view('admin.report.salary', [
            'title'        => 'Salary Report',
            'employee'     => Employee::orderBy('emp_name')->get(),
            'dataTableURL' => route('admin.report.salary'),
            'table'        => collect([
                'employee'      => 'Employee',
                'month'         => 'Month',
                'salary'        => 'Per Day Salary',
                'days_present'  => 'No. of Days Present',
                'amount_taken'  => 'Amount Taken',
                'due_salary'    => 'Due Salary',
            ]),
        ]);
    }
}
