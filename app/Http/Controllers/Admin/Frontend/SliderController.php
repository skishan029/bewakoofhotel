<?php

namespace App\Http\Controllers\Admin\Frontend;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Slider::latest()->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div class="btn-group">';

                $url = route('admin.frontend.slider.edit', ['id'=> $row->id]);
                $btn .= \App\Helper\Helper::commonEditButton($url);

                $btn .= "<button class='btn btn-sm btn-danger btn-flat' value='{$row->id}' type='button' title='Delete' onclick='deleteAppSlider(this)'><i class='fas fa-trash-alt'></i></button>";

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
                'tag'           => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            $slider = new \App\Models\Slider();
            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    $slider->thumbnail = $request->file('thumbnail')->store('sliders');
                }
            }
            if (isset($_FILES['thumbnail_two'])) {
                if ($_FILES['thumbnail_two']['error'] == 0) {
                    $slider->thumbnail_two = $request->file('thumbnail_two')->store('sliders');
                }
            }
            $slider->tag = (empty($request->tag)) ? NULL : strip_tags(trim($request->tag));
            $slider->save();
            return response()->json(['type'=>'success', 'message'=> "Files are uploaded successfully"]);
            
        }
        $data['table'] = collect([
            'thumbnail'     =>'Thumbnail',
            'thumbnail_two' =>'Thumbnail Two',
            'tag'           =>'Tag',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.slider.upload');
        $data['dataTableURL'] = route('admin.frontend.slider.index');

        $data['title'] = 'Upload & All Sliders';
        return view('admin.frontend.slider.index', $data);
    }
    function edit(Request $request, $id)
    {

        $slider = \App\Models\Slider::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'thumbnail_two' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'tag'           => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if (blank($slider)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }

            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    \App\Helper\Helper::customFileDelete($slider->thumbnail);
                    $slider->thumbnail = $request->file('thumbnail')->store('sliders');
                }
            }
            if (isset($_FILES['thumbnail_two'])) {
                if ($_FILES['thumbnail_two']['error'] == 0) {
                    \App\Helper\Helper::customFileDelete($slider->thumbnail_two);
                    $slider->thumbnail_two = $request->file('thumbnail_two')->store('sliders');
                }
            }
            $slider->tag = (empty($request->tag)) ? NULL : strip_tags(trim($request->tag));
            $slider->save();

            return response()->json([
                'type'=>'success', 
                'message'=> "Files are updated successfully",
                'thumbnail_two'=> (empty($slider->thumbnail_two)) ? NULL : Storage::url($slider->thumbnail_two),
                'thumbnail'=> (empty($slider->thumbnail)) ? NULL : Storage::url($slider->thumbnail),
            ]);
            
        }

        if (blank($slider)) {
            return redirect()->route('admin.frontend.slider.upload');
        }
        $data['table'] = collect([
            'thumbnail'     =>'Thumbnail',
            'thumbnail_two' =>'Thumbnail Two',
            'tag'           =>'Tag',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.slider.edit', ['id'=> $id]);
        $data['dataTableURL'] = route('admin.frontend.slider.index');
        $data['slider'] = $slider;
        $data['title'] = 'Edit Upload & All Sliders';
        return view('admin.frontend.slider.index', $data);
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
            $appSlider = \App\Models\Slider::find($request->id);
            if (blank($appSlider)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }
            \App\Helper\Helper::customFileDelete($appSlider->thumbnail);
            \App\Helper\Helper::customFileDelete($appSlider->thumbnail_two);
            $appSlider->delete();

            return response()->json(['type'=>'success', 'message'=> "Successfully deleted"]);
        }
    }
}
