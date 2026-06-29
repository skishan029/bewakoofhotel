<?php

namespace App\Http\Controllers\Admin\Frontend;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Testimonial::withTrashed()->orderByRaw('-deleted_at asc')->latest()->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<div class="btn-group">';

                if ($row->trashed()) {
                    $btn .= \App\Helper\Helper::commonDisableEditButton();
                    $btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '1', '2');
                } else {
                    $url = route('admin.frontend.testimonial.edit', ['id'=> $row->id]);
                    $btn .= \App\Helper\Helper::commonEditButton($url);
                    $btn .= \App\Helper\Helper::commonDeleteRestoreButton($row->id, '2', '1');
                }

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
    function create(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'description'   => 'nullable|string',
                'title'         => 'nullable|string',
                'rating'        =>"required|integer",
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            $testimonial = new \App\Models\Testimonial();
            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    $testimonial->thumbnail = $request->file('thumbnail')->store('testimonial');
                }
            }
            $testimonial->title        = (empty($request->title)) ? NULL : strip_tags(trim($request->title));
            $testimonial->description  = (empty($request->description)) ? NULL : strip_tags(trim($request->description));
            $testimonial->rating        = (empty($request->rating)) ? NULL : strip_tags(trim($request->rating));

            $testimonial->save();
            return response()->json(['type'=>'success', 'message'=> "Successfully created testimonial"]);
            
        }
        $data['table'] = collect([
            'title'         =>'Title',
            'thumbnail'     =>'Thumbnail',
            'description'   =>'Description',
            'rating'        =>'Rating',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.testimonial.create');
        $data['dataTableURL'] = route('admin.frontend.testimonial.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.testimonial');

        $data['title'] = 'Create & All Testimonials';
        return view('admin.frontend.testimonial.index', $data);
    }
    function edit(Request $request, $id)
    {

        $testimonial = \App\Models\Testimonial::find($id);
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'description'   => 'nullable|string',
                'title'         => 'nullable|string',
                'rating'        =>"required|integer",
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if (blank($testimonial)) {
                return response()->json(['type'=>'error', 'message'=> "Invalid id"]);
            }

            if (isset($_FILES['thumbnail'])) {
                if ($_FILES['thumbnail']['error'] == 0) {
                    \App\Helper\Helper::customFileDelete($testimonial->thumbnail);
                    $testimonial->thumbnail = $request->file('thumbnail')->store('testimonial');
                }
            }
            $testimonial->title        = (empty($request->title)) ? NULL : strip_tags(trim($request->title));
            $testimonial->description  = (empty($request->description)) ? NULL : strip_tags(trim($request->description));
            $testimonial->rating        = (empty($request->rating)) ? NULL : strip_tags(trim($request->rating));
            $testimonial->save();

            return response()->json([
                'type'=>'success', 
                'message'=> "Successfully update testimonial",
                'thumbnail'=> (empty($testimonial->thumbnail)) ? NULL : Storage::url($testimonial->thumbnail),
            ]);
            
        }

        if (blank($testimonial)) {
            return redirect()->route('admin.frontend.testimonial.create');
        }
        $data['table'] = collect([
            'title'         =>'Title',
            'thumbnail'     =>'Thumbnail',
            'description'   =>'Description',
            'rating'        =>'Rating',
            'action'        =>'Action',
        ]);
        $data['submitURL'] = route('admin.frontend.testimonial.edit', ['id'=> $id]);
        $data['dataTableURL'] = route('admin.frontend.testimonial.index');
        $data['changeStatusURL'] = route('admin.ajax.changestatus.testimonial');
        
        $data['testimonial'] = $testimonial;
        $data['title'] = 'Edit & All Testimonial';

        return view('admin.frontend.testimonial.index', $data);
    }
}
