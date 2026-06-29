<?php

namespace App\Http\Controllers\Admin\ProductMaster;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\ProductCategory::withTrashed()->orderByRaw('parent_id asc')->orderByRaw('-deleted_at asc')->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group">';

                    if ($row->trashed()) {
                        $btn .= \App\Helper\Helper::commonDisableEditButton();
                        $btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '1', '2');
                    } else {
                        $url = route('admin.productmaster.category.edit', ['id' => $row->id]);
                        $btn .= \App\Helper\Helper::commonEditButton($url);
                        $btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '2', '1');
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at));
                })
                ->editColumn('cat_title', function ($row) {
                    return $row->cat_title;
                })
                ->rawColumns(['action', 'created_at', 'cat_title'])
                ->make(true);
        }
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $validator =  validator($request->all(), [
                "cat_title"     => "required|string",
                "cat_desc"      => "nullable|string",
                "parent_id"     => "nullable|integer",
                "cat_thumbnail" => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
            ]);
            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                $productCategory = new \App\Models\ProductCategory();
                $productCategory->cat_title = strip_tags($request->cat_title);
                $productCategory->parent_id = (empty($request->parent_id)) ? NULL : strip_tags($request->parent_id);
                $productCategory->cat_desc = (empty($request->cat_desc)) ? NULL : strip_tags($request->cat_desc);
                $productCategory->save();

                if (isset($_FILES['cat_thumbnail'])) {
                    if ($_FILES['cat_thumbnail']['error'] == 0) {
                        $productCategory->cat_thumbnail = strip_tags($request->file('cat_thumbnail')->store('category'));
                        $productCategory->save();
                    }
                }

                $parentCategorys = \App\Models\ProductCategory::select(['id', 'cat_title'])->whereNull('parent_id')->get();
                $output = ['type' => 'success', 'message' => 'Successfully create product category', 'parentcategorys' => $parentCategorys];
            }
            return response()->json($output);
        }
        $data['title'] = 'Create Category';
        $data['table'] = collect([
            'created_at' => 'C. Date',
            'cat_title' => 'Title',
            'action'    => 'Action',
        ]);
        $data['submitURL'] = route('admin.productmaster.category.create');
        $data['dataTableURL'] = route('admin.productmaster.category.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.productcategory');

        return view('admin.productmaster.category.category', $data);
    }
    public function edit(Request $request, $id)
    {
        $productCategory = \App\Models\ProductCategory::find($id);

        if ($request->ajax()) {
            $validator =  validator($request->all(), [
                "cat_title"     => "required|string",
                "cat_desc"      => "nullable|string",
                "parent_id"     => "nullable|integer",
                "cat_thumbnail" => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
            ]);
            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {

                if (blank($productCategory)) {
                    return response()->json(['type' => 'error', 'message' => 'Invalid id']);
                }
                $productCategory->cat_title = strip_tags($request->cat_title);
                $productCategory->parent_id = (empty($request->parent_id)) ? NULL : strip_tags($request->parent_id);
                $productCategory->cat_desc = (empty($request->cat_desc)) ? NULL : strip_tags($request->cat_desc);
                $productCategory->save();

                if (isset($_FILES['cat_thumbnail'])) {
                    if ($_FILES['cat_thumbnail']['error'] == 0) {
                        $oldcat_thumbnail = $productCategory->cat_thumbnail;
                        $productCategory->cat_thumbnail = strip_tags($request->file('cat_thumbnail')->store('category'));
                        $productCategory->save();

                        \App\Helper\Helper::customFileDelete($oldcat_thumbnail);
                    }
                }

                $cat_thumbnail = (empty($productCategory->cat_thumbnail)) ? NULL : Storage::url($productCategory->cat_thumbnail);

                //$parentCategorys = \App\Models\ProductCategory::select(['id','cat_title'])->where('id', '!=', $id)->whereNull('parent_id')->get();
                $output = ['type' => 'success', 'message' => 'Successfully updated product category', 'cat_thumbnail' => $cat_thumbnail];
            }
            return response()->json($output);
        }
        if (blank($productCategory)) {
            return redirect()->route('admin.productmaster.category.create');
        }

        $data['title'] = 'Edit Category';
        $data['table'] = collect([
            'created_at' => 'C. Date',
            'cat_title' => 'Title',
            'action'    => 'Action',
        ]);
        $data['submitURL'] = route('admin.productmaster.category.edit', ['id' => $id]);
        $data['dataTableURL'] = route('admin.productmaster.category.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.productcategory');

        //$data['parentCategorys'] = \App\Models\ProductCategory::select(['id','cat_title'])->where('id', '!=', $id)->whereNull('parent_id')->get();
        $data['productCategory'] = $productCategory;

        return view('admin.productmaster.category.category', $data);
    }
}
