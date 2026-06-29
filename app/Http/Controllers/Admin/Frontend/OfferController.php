<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function offerSet(Request $request){
      $offerdiscounts =  \Illuminate\Support\Facades\DB::table('offer_discounts')->first();

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'offer_image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'back_image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'percent'         => 'nullable|string',
                'offer_text'      => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if(blank($offerdiscounts)){

                $offer_image = NULL;
                if (isset($_FILES['offer_image'])) {
                    if($_FILES['offer_image']['error'] == 0){
                        $offer_image = strip_tags($request->file('offer_image')->store('offer'));
                    }
                }
                $back_image = NULL;
                if (isset($_FILES['back_image'])) {
                    if($_FILES['back_image']['error'] == 0){
                        $back_image = strip_tags($request->file('back_image')->store('offer'));
                    }
                }

                \Illuminate\Support\Facades\DB::table('offer_discounts')->insert([
                    'offer_image'    => (empty($offer_image)) ? NULL : strip_tags(trim($offer_image)),
                    'back_image'    => (empty($back_image)) ? NULL : strip_tags(trim($back_image)),
                    'percent'    => (empty($request->percent)) ? NULL : strip_tags(trim($request->percent)),
                    'offer_text'    => (empty($request->offer_text)) ? NULL : strip_tags(trim($request->offer_text)),
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);

            }else{

                $offer_image = $offerdiscounts->offer_image;
                if (isset($_FILES['offer_image'])) {
                    if($_FILES['offer_image']['error'] == 0){
                        \App\Helper\Helper::customFileDelete($offerdiscounts->offer_image);
                        $offer_image = strip_tags($request->file('offer_image')->store('offer'));
                    }
                }
                $back_image = $offerdiscounts->back_image;
                if (isset($_FILES['back_image'])) {
                    if($_FILES['back_image']['error'] == 0){
                        \App\Helper\Helper::customFileDelete($offerdiscounts->back_image);
                        $back_image = strip_tags($request->file('back_image')->store('offer'));
                    }
                }

                \Illuminate\Support\Facades\DB::table('offer_discounts')->where('id', $offerdiscounts->id)->update([
                    'offer_image'    => (empty($offer_image)) ? NULL : strip_tags(trim($offer_image)),
                    'back_image'    => (empty($back_image)) ? NULL : strip_tags(trim($back_image)),
                    'percent'    => (empty($request->percent)) ? NULL : strip_tags(trim($request->percent)),
                    'offer_text'    => (empty($request->offer_text)) ? NULL : strip_tags(trim($request->offer_text)),
                    'updated_at'            => now(),

                ]);

            }
           
            return response()->json(['type'=>'success', 'message'=> "Files are uploaded successfully"]);
            
        }

        $data['offerdiscounts'] = $offerdiscounts;
       
        $data['submitURL'] = route('admin.frontend.offer.create');        
        $data['title'] = 'Offer Discount';
        return view('admin.frontend.offer.offerSet',$data);
    }

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
