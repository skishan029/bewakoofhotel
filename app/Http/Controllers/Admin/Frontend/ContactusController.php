<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class ContactusController extends Controller
{
   public function imageAdd(Request $request){

        $contactusimages =  \Illuminate\Support\Facades\DB::table('contactus_images')->first();

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'front_image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'background_image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                
            ]);
            if ($validator->fails()) {
                return response()->json(['type'=>'error', 'message'=> $validator->errors()->all()]);
            }

            if(blank($contactusimages)){

                $front_image = NULL;
                if (isset($_FILES['front_image'])) {
                    if($_FILES['front_image']['error'] == 0){
                        $front_image = strip_tags($request->file('front_image')->store('contactus'));
                    }
                }
                $background_image = NULL;
                if (isset($_FILES['background_image'])) {
                    if($_FILES['background_image']['error'] == 0){
                        $background_image = strip_tags($request->file('background_image')->store('contactus'));
                    }
                }

                \Illuminate\Support\Facades\DB::table('contactus_images')->insert([
                    'front_image'    => (empty($front_image)) ? NULL : strip_tags(trim($front_image)),
                    'background_image'    => (empty($background_image)) ? NULL : strip_tags(trim($background_image)),
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);

            }else{

                $front_image = $contactusimages->front_image;
                if (isset($_FILES['front_image'])) {
                    if($_FILES['front_image']['error'] == 0){
                        \App\Helper\Helper::customFileDelete($contactusimages->front_image);
                        $front_image = strip_tags($request->file('front_image')->store('contactus'));
                    }
                }
                $background_image = $contactusimages->background_image;
                if (isset($_FILES['background_image'])) {
                    if($_FILES['background_image']['error'] == 0){
                        \App\Helper\Helper::customFileDelete($contactusimages->background_image);
                        $background_image = strip_tags($request->file('background_image')->store('contactus'));
                    }
                }

                \Illuminate\Support\Facades\DB::table('contactus_images')->where('id', $contactusimages->id)->update([
                    'front_image'    => (empty($front_image)) ? NULL : strip_tags(trim($front_image)),
                    'background_image'    => (empty($background_image)) ? NULL : strip_tags(trim($background_image)),
                    'updated_at'            => now(),
                ]);
            }
        
            return response()->json(['type'=>'success', 'message'=> "Files are uploaded successfully"]);
            
        }
        $data['contactusimages'] = $contactusimages;

    $data['title'] = 'Contact Image Change';
    $data['submitURL'] = route('admin.frontend.contactus.create');
    return view('admin.frontend.contactus.imagechange',$data);

   }

   function index(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\ContactusImage::latest()->get();

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
            ->editColumn('front_image', function($row){
                $html = '';
                if (!empty($row->front_image)) {
                    $html .= '<a href="'.Storage::url($row->front_image).'" target="_blank">';
                    $html .= '<img class="img-thumbnail" src="'.Storage::url($row->front_image).'" style="width: 50px; height: 50px">';
                    $html .= '</a>';
                }

                return $html;
            })
            ->editColumn('background_image', function($row){
                $html = '';
                if (!empty($row->background_image)) {
                    $html .= '<a href="'.Storage::url($row->background_image).'" target="_blank">';
                    $html .= '<img class="img-thumbnail" src="'.Storage::url($row->background_image).'" style="width: 50px; height: 50px">';
                    $html .= '</a>';
                }

                return $html;
            })
            ->rawColumns(['action','front_image','background_image'])
            ->make(true);
        }

    }
}
