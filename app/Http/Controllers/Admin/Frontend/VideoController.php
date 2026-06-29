<?php

namespace App\Http\Controllers\Admin\Frontend;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function video(Request $request){

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'link'     => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            

            $video = new \App\Models\Video();
            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {                    
                    $video->thumbnail = $request->file('thumbnail')->store('video_image');
                }
            }
           
            $video->link = (empty($request->link)) ? NULL : strip_tags(trim($request->link));
            $video->save();
            return response()->json(['type'=>'success', 'message'=> "Files are uploaded successfully"]);
            
        }
        $data['table'] = collect([
            'link'     =>'Video Link',
            'thumbnail' =>'Thumbnail',
            // 'tag'           =>'Tag',
            'action'        =>'Action',
        ]);

        $data['submitURL'] = route('admin.frontend.video.upload');
        $data['dataTableURL'] = route('admin.frontend.video.index');

        $data['title'] = 'Video';
        return view('admin.frontend.video.addvideo',$data);

    }

    function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Video::latest()->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div class="btn-group">';

                $url = route('admin.frontend.video.edit', ['id'=> $row->id]);
                $btn .= \App\Helper\Helper::commonEditButton($url);

                $btn .= "<button class='btn btn-sm btn-danger btn-flat' value='{$row->id}' type='button' title='Delete' onclick='deleteAppSlider(this)'><i class='fas fa-trash-alt'></i></button>";

                $btn .= '</div>';
                return $btn;
            })
            ->editColumn('link', function($row){
                // $html = '';
                // if (!empty($row->thumbnail)) {
                //     $html .= '<a href="'.Storage::url($row->thumbnail).'" target="_blank">';
                //     $html .= '<img class="img-thumbnail" src="'.Storage::url($row->thumbnail).'" style="width: 50px; height: 50px">';
                //     $html .= '</a>';
                // }

                return $row->link;
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
            ->rawColumns(['action','link','thumbnail'])
            ->make(true);
        }

    }
    // function upload(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $validator = validator($request->all(), [
    //             'link'     => 'required',
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
    //         }

    //         $video = new \App\Models\Video();
           
    //         $video->tag = (empty($request->link)) ? NULL : strip_tags(trim($request->link));
    //         $video->save();
    //         return response()->json(['type'=>'success', 'message'=> "Files are uploaded successfully"]);
            
    //     }
    //     $data['table'] = collect([
    //         'link'     =>'Video Link',
    //         // 'thumbnail_two' =>'Thumbnail Two',
    //         // 'tag'           =>'Tag',
    //         'action'        =>'Action',
    //     ]);
    //     $data['submitURL'] = route('admin.frontend.slider.upload');
    //     $data['dataTableURL'] = route('admin.frontend.slider.index');

    //     $data['title'] = 'Upload & All Sliders';
    //     return view('admin.frontend.slider.index', $data);
    // }
    function edit(Request $request, $id)
    {

        $video = \App\Models\Video::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'link'     => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if (blank($video)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }

            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    \App\Helper\Helper::customFileDelete($video->thumbnail);
                    $video->thumbnail = $request->file('thumbnail')->store('video_image');
                }
            }
           
            $video->link = (empty($request->link)) ? NULL : strip_tags(trim($request->link));
            $video->save();

            return response()->json([
                'type'=>'success', 
                'message'=> "Files are updated successfully",                
                'thumbnail'=> (empty($video->thumbnail)) ? NULL : Storage::url($video->thumbnail),
            ]);
            
        }

        if (blank($video)) {
            return redirect()->route('admin.frontend.video.upload');
        }
        $data['table'] = collect([
            'thumbnail'     =>'Thumbnail',
           
            'link'           =>'Link',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.video.edit', ['id'=> $id]);
        $data['dataTableURL'] = route('admin.frontend.video.index');
        $data['video'] = $video;
        $data['title'] = 'Edit Video';
        return view('admin.frontend.video.addvideo', $data);
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
            $video = \App\Models\Video::find($request->id);
            if (blank($video)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }
            \App\Helper\Helper::customFileDelete($video->thumbnail);
            $video->delete();

            return response()->json(['type'=>'success', 'message'=> "Successfully deleted"]);
        }
    }
}
