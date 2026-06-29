<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function create(Request $request){
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'emp_name'          => 'required',
                'contact_no'        => 'nullable',
                'salary'            => 'nullable|numeric|min:0',
                'address'           => 'nullable|',
                'aadhar_no'         => 'nullable|',
                'profile_photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            $employee = new \App\Models\Employee();
            
            if (isset($_FILES['profile_photo'])) {
                if ($_FILES['profile_photo']['error'] == 0) {
                    $employee->profile_photo = $request->file('profile_photo')->store('profile_photo');
                }
            }
            $employee->emp_name = (empty($request->emp_name)) ? NULL : strip_tags(trim($request->emp_name));
            $employee->contact_no = (empty($request->contact_no)) ? NULL : strip_tags(trim($request->contact_no));
            $employee->salary = (empty($request->salary)) ? NULL : $request->salary;
            $employee->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));
            $employee->aadhar_no = (empty($request->aadhar_no)) ? NULL : strip_tags(trim($request->aadhar_no));
            $employee->save();

            return response()->json(['type'=>'success', 'message'=> "Files are uploaded successfully"]);
        }

        $data['submitURL'] = route('admin.employee.create');
        $data['title'] = "Employee Create";
        return view('admin.employee.create',$data);

    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Employee::withTrashed()->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';

                    if ($row->trashed()) {
                        $btn .= "<button class='btn btn-sm btn-success' onclick='restoreAppEmployee(this)' value='{$row->id}'><i class='fas fa-undo'></i></button>";
                    } else {
                        $editUrl = route('admin.employee.edit', ['id' => $row->id]);
                        $btn .= \App\Helper\Helper::commonEditButton($editUrl);
                        $btn .= "<button class='btn btn-sm btn-danger' onclick='deleteAppEmployee(this)' value='{$row->id}'><i class='fas fa-trash-alt'></i></button>";
                    }

                    $btn .= '</div>';
                    return $btn;
                })

                ->editColumn('created_date', function ($row) {
                    return $row->created_at ? date('d-m-Y', strtotime($row->created_at)) : '-';
                })

                ->editColumn('name', fn($row) => $row->emp_name)
                ->editColumn('contact', fn($row) => $row->contact_no)
                ->editColumn('salary', fn($row) => $row->salary ?? '-')
                ->editColumn('address', fn($row) => $row->address)
                ->editColumn('aadhar', fn($row) => $row->aadhar_no)

                ->addColumn('status', function ($row) {
                    return $row->trashed()
                        ? '<span class="badge badge-danger">Inactive</span>'
                        : '<span class="badge badge-success">Active</span>';
                })

                ->editColumn('profile_photo', function ($row) {
                    if (!empty($row->profile_photo)) {
                        $url = Storage::url($row->profile_photo);
                        return "<a href='{$url}' target='_blank'><img class='img-thumbnail' src='{$url}' style='width:50px;height:50px'></a>";
                    }
                    return '';
                })

                ->rawColumns(['action', 'status', 'profile_photo'])
                ->make(true);
        }

        $data['table'] = collect([
            'created_date'    => 'C. Date',
            'name'            => 'Name',
            'contact'         => 'Contact No',
            'salary'          => 'Salary',
            'address'         => 'Address',
            'aadhar'          => 'Aadhar No',
            'profile_photo'   => 'Profile Image',
            'status'          => 'Status',
            'action'          => 'Action',
        ]);

        $data['dataTableURL'] = route('admin.employee.index');
        $data['title'] = "All Employee";

        return view('admin.employee.index', $data);
    }

    public function edit(Request $request, $id){
        $employee = \App\Models\Employee::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'emp_name'          => 'required',
                'contact_no'        => 'nullable',
                'salary'            => 'nullable|numeric|min:0',
                'address'           => 'nullable|',
                'aadhar_no'         => 'nullable|',
                'profile_photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if (isset($_FILES['profile_photo'])) {
                if ($_FILES['profile_photo']['error'] == 0) {
                    $employee->profile_photo = $request->file('profile_photo')->store('profile_photo');
                }
            }
            $employee->emp_name = (empty($request->emp_name)) ? NULL : strip_tags(trim($request->emp_name));
            $employee->contact_no = (empty($request->contact_no)) ? NULL : strip_tags(trim($request->contact_no));
            $employee->salary = (empty($request->salary)) ? NULL : $request->salary;
            $employee->address = (empty($request->address)) ? NULL : strip_tags(trim($request->address));
            $employee->aadhar_no = (empty($request->aadhar_no)) ? NULL : strip_tags(trim($request->aadhar_no));
            $employee->save();
            return response()->json(['type'=>'success',
                'message'=> " updated successfully",
                'profile_photo'=> (empty($employee->profile_photo)) ? NULL : Storage::url($employee->profile_photo),
            ]);
            
        }

        $data['submitURL'] = route('admin.employee.edit', ['id'=> $id]);
        $data['employee'] = $employee;
        $data['title'] = "Employee Edit";
        return view('admin.employee.create',$data);

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
            $appEmployee = \App\Models\Employee::find($request->id);
            if (blank($appEmployee)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }
            
            \App\Helper\Helper::customFileDelete($appEmployee->profile_photo);
            $appEmployee->delete();

            return response()->json(['type'=>'success', 'message'=> "Successfully deleted"]);
        }
    }

    public function restore(Request $request) 
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'id'=> 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            $employee = \App\Models\Employee::onlyTrashed()->find($request->id);

            if (blank($employee)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid or already restored ID"]);
            }
            $employee->restore();

            return response()->json(['type'=>'success', 'message'=> "Successfully restored"]);
        }
    }

}
