<?php

namespace App\Http\Controllers\Admin\Frontend;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\FoodItem::latest()->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div class="btn-group">';

                $url = route('admin.frontend.fooditem.edit', ['id'=> $row->id]);
                $btn .= \App\Helper\Helper::commonEditButton($url);

                $btn .= "<button class='btn btn-sm btn-danger btn-flat' value='{$row->id}' type='button' title='Delete' onclick='deleteFoodItem(this)'><i class='fas fa-trash-alt'></i></button>";

                $btn .= '</div>';
                return $btn;
            })
            ->editColumn('thumbnail', function($row){
                $html = '';
                if (!empty($row->thumbnail)) {
                    $html .= '<a href="'.Storage::url($row->thumbnail).'" target="_blank">';
                    $html .= '<img class="img-thumbnail" src="'.Storage::url($row->thumbnail).'" style="width: 50px; height: 50px">';
                    $html .= '</a>';
                }

                return $html;
            })

            ->editColumn('thumbnail_two', function($row){
                $html = '';
                if (!empty($row->thumbnail_two)) {
                    $html .= '<a href="'.Storage::url($row->thumbnail_two).'" target="_blank">';
                    $html .= '<img class="img-thumbnail" src="'.Storage::url($row->thumbnail_two).'" style="width: 50px; height: 50px">';
                    $html .= '</a>';
                }

                return $html;
            })
            ->rawColumns(['action','thumbnail','thumbnail_two'])
            ->make(true);
        }

    }
    function upload(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'thumbnail_two' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'description'   => 'nullable|string',
                'title'         => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            $foodItem = new \App\Models\FoodItem();
            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    $foodItem->thumbnail = $request->file('thumbnail')->store('fooditem');
                }
            }
            if (isset($_FILES['thumbnail_two'])) {
                if ($_FILES['thumbnail_two']['error'] == 0) {
                    $foodItem->thumbnail_two = $request->file('thumbnail_two')->store('fooditem');
                }
            }
            $foodItem->title        = (empty($request->title)) ? NULL : strip_tags(trim($request->title));
            $foodItem->description  = (empty($request->description)) ? NULL : strip_tags(trim($request->description));
            $foodItem->save();
            return response()->json(['type'=>'success', 'message'=> "Files are uploaded successfully"]);
            
        }
        $data['table'] = collect([
            'title'         =>'Title',
            'thumbnail'     =>'Thumbnail',
            'thumbnail_two'     =>'Thumbnail Two',
            'description'   =>'Description',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.fooditem.upload');
        $data['dataTableURL'] = route('admin.frontend.fooditem.index');

        $data['title'] = 'Upload & All Food Item';
        return view('admin.frontend.fooditem.index', $data);
    }
    function edit(Request $request, $id)
    {

        $foodItem = \App\Models\FoodItem::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'thumbnail_two'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'description'   => 'nullable|string',
                'title'         => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if (blank($foodItem)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }

            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    \App\Helper\Helper::customFileDelete($foodItem->thumbnail);
                    $foodItem->thumbnail = $request->file('thumbnail')->store('fooditem');
                }
            }
            if (isset($_FILES['thumbnail_two'])) {
                if ($_FILES['thumbnail_two']['error'] == 0) {
                    \App\Helper\Helper::customFileDelete($foodItem->thumbnail_two);
                    $foodItem->thumbnail_two = $request->file('thumbnail_two')->store('fooditem');
                }
            }
            $foodItem->title        = (empty($request->title)) ? NULL : strip_tags(trim($request->title));
            $foodItem->description  = (empty($request->description)) ? NULL : strip_tags(trim($request->description));
            $foodItem->save();

            return response()->json([
                'type'=>'success', 
                'message'=> "Files are updated successfully",
                'thumbnail'=> (empty($foodItem->thumbnail)) ? NULL : Storage::url($foodItem->thumbnail),
                'thumbnail_two'=> (empty($foodItem->thumbnail_two)) ? NULL : Storage::url($foodItem->thumbnail_two),
            ]);
            
        }

        if (blank($foodItem)) {
            return redirect()->route('admin.frontend.fooditem.upload');
        }
        $data['table'] = collect([
            'title'         =>'Title',
            'thumbnail'     =>'Thumbnail',
            'thumbnail_two' =>'Thumbnail Two',
            'description'   =>'Description',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.fooditem.edit', ['id'=> $id]);
        $data['dataTableURL'] = route('admin.frontend.fooditem.index');
        $data['foodItem'] = $foodItem;
        $data['title'] = 'Edit Upload & All Food Items';
        return view('admin.frontend.fooditem.index', $data);
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
            $foodItem = \App\Models\FoodItem::find($request->id);
            if (blank($foodItem)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }
            \App\Helper\Helper::customFileDelete($foodItem->thumbnail);
            \App\Helper\Helper::customFileDelete($foodItem->thumbnail_two);
            $foodItem->delete();

            return response()->json(['type'=>'success', 'message'=> "Successfully deleted"]);
        }
    }
}
