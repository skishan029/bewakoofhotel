<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExpenceController extends Controller
{

    function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Expence::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';

                    $url = route('admin.expence.edit', ['id' => $row->id]);
                    $btn .= \App\Helper\Helper::commonEditButton($url);

                    $btn .= "<button class='btn btn-sm btn-danger btn-flat' value='{$row->id}' type='button' title='Delete' onclick='deleteAppSlider(this)'><i class='fas fa-trash-alt'></i></button>";

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'thumbnail', 'thumbnail_two'])
                ->make(true);
        }
    }
    function expenceAdd(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'exp_date'     => 'required|',
                'exp_amount' => 'required|',
                'purpose'           => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            $expence = new \App\Models\Expence();

            $expence->exp_date = (empty($request->exp_date)) ? NULL : strip_tags(trim($request->exp_date));
            $expence->exp_amount = (empty($request->exp_amount)) ? NULL : strip_tags(trim($request->exp_amount));
            $expence->purpose = (empty($request->purpose)) ? NULL : strip_tags(trim($request->purpose));
            $expence->save();
            return response()->json(['type' => 'success', 'message' => " successfully"]);
        }
        $data['table'] = collect([
            'exp_date'     => 'Date',
            'exp_amount' => 'Amount',
            'purpose'           => 'Purpose',
            'action'        => 'Action',
        ]);
        $data['submitURL'] = route('admin.expence.expenceadd');
        $data['dataTableURL'] = route('admin.expence.index');

        $data['title'] = 'Expence';
        return view('admin.expence.addexpence', $data);
    }
    function edit(Request $request, $id)
    {

        $expence = \App\Models\Expence::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'exp_date'     => 'required|',
                'exp_amount' => 'required|',
                'purpose'           => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            if (blank($expence)) {
                return response()->json(['type' => 'error', 'message' => "Invalid id"]);
            }

            $expence->exp_date = (empty($request->exp_date)) ? NULL : strip_tags(trim($request->exp_date));
            $expence->exp_amount = (empty($request->exp_amount)) ? NULL : strip_tags(trim($request->exp_amount));
            $expence->purpose = (empty($request->purpose)) ? NULL : strip_tags(trim($request->purpose));
            $expence->save();

            return response()->json([
                'type' => 'success',
                'message' => "updated successfully",
            ]);
        }

        if (blank($expence)) {
            return redirect()->route('admin.expence.expenceadd');
        }
        $data['table'] = collect([
            'exp_date'     => 'Date',
            'exp_amount' => 'Amount',
            'purpose'           => 'Purpose',
            'action'        => 'Action',
        ]);
        $data['submitURL'] = route('admin.expence.edit', ['id' => $id]);
        $data['dataTableURL'] = route('admin.expence.index');
        $data['expence'] = $expence;
        $data['title'] = 'Edit Expence & All Expence';
        return view('admin.expence.addexpence', $data);
    }
    function delete(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }
            $appExpence = \App\Models\Expence::find($request->id);
            if (blank($appExpence)) {
                return response()->json(['type' => 'error', 'message' => "Invalid id"]);
            }
            $appExpence->delete();

            return response()->json(['type' => 'success', 'message' => "Successfully deleted"]);
        }
    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start = date('Y-m-d', strtotime($request->start_date));
                $end = date('Y-m-d', strtotime($request->end_date));

                $data = \App\Models\Expence::whereDate('exp_date', '>=', $start)
                    ->whereDate('exp_date', '<=', $end)
                    ->latest()
                    ->get();
            } else {
                $start = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                $end = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');

                $data = \App\Models\Expence::whereDate('exp_date', '>=', $start)
                    ->whereDate('exp_date', '<=', $end)
                    ->latest()
                    ->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('exp_date', function ($row) {
                    return date('d-m-Y', strtotime($row->exp_date));
                })
                ->editColumn('purpose', function ($row) {
                    return $row->purpose ?? '-';
                })
                ->editColumn('exp_amount', function ($row) {
                    return number_format($row->exp_amount, 2, '.', ''); // no commas
                })
                ->rawColumns(['exp_date', 'exp_amount', 'purpose'])
                ->make(true);
        }

        // Adjust the column order here
        $data['table'] = collect([
            'exp_date'    => 'Date',
            'purpose'     => 'Purpose',
            'exp_amount'  => 'Amount',
        ]);
        $data['title'] = 'Expense Report';
        $data['dataTableURL'] = route('admin.report.expense');
        return view('admin.report.expense', $data);
    }
}
