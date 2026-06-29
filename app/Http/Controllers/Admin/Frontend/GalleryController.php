<?php

namespace App\Http\Controllers\Admin\Frontend;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Gallery::latest()->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div class="btn-group">';

                $url = route('admin.frontend.gallery.edit', ['id'=> $row->id]);
                $btn .= \App\Helper\Helper::commonEditButton($url);

                $btn .= "<button class='btn btn-sm btn-danger btn-flat' value='{$row->id}' type='button' title='Delete' onclick='deleteGallery(this)'><i class='fas fa-trash-alt'></i></button>";

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
            ->rawColumns(['action','thumbnail'])
            ->make(true);
        }

    }
    function upload(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'tag'           => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            $gallery = new \App\Models\Gallery();
            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    $gallery->thumbnail = $request->file('thumbnail')->store('gallery');
                }
            }
            $gallery->tag = (empty($request->tag)) ? NULL : strip_tags(trim($request->tag));
            $gallery->save();
            return response()->json(['type'=>'success', 'message'=> "Files are uploaded successfully"]);
            
        }
        $data['table'] = collect([
            'thumbnail'     =>'Thumbnail',
            'tag'           =>'Tag',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.gallery.upload');
        $data['dataTableURL'] = route('admin.frontend.gallery.index');

        $data['title'] = 'Upload & All Gallery';
        return view('admin.frontend.gallery.index', $data);
    }
    function edit(Request $request, $id)
    {

        $gallery = \App\Models\Gallery::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'tag'           => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if (blank($gallery)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }

            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    \App\Helper\Helper::customFileDelete($gallery->thumbnail);
                    $gallery->thumbnail = $request->file('thumbnail')->store('gallery');
                }
            }
            $gallery->tag = (empty($request->tag)) ? NULL : strip_tags(trim($request->tag));
            $gallery->save();

            return response()->json([
                'type'=>'success', 
                'message'=> "Files are updated successfully",
                'thumbnail'=> (empty($gallery->thumbnail)) ? NULL : Storage::url($gallery->thumbnail),
            ]);
            
        }

        if (blank($gallery)) {
            return redirect()->route('admin.frontend.gallery.upload');
        }
        $data['table'] = collect([
            'thumbnail'     =>'Thumbnail',
            'tag'           =>'Tag',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.gallery.edit', ['id'=> $id]);
        $data['dataTableURL'] = route('admin.frontend.gallery.index');
        $data['gallery'] = $gallery;
        $data['title'] = 'Edit Upload & All Gallery';
        return view('admin.frontend.gallery.index', $data);
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
            $gallery = \App\Models\Gallery::find($request->id);
            if (blank($gallery)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }
            \App\Helper\Helper::customFileDelete($gallery->thumbnail);
            $gallery->delete();

            return response()->json(['type'=>'success', 'message'=> "Successfully deleted"]);
        }
    }
}
