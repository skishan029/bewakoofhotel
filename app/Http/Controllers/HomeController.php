<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index(Request $request)
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
            return response()->json(['type'=>'success', 'message'=> "Successfully submit your enquiry"]);
            
        }


        $data['sliders'] = \App\Models\Slider::get();
        $data['foodItems'] = \App\Models\FoodItem::get();
        $data['allProducts'] = \App\Models\Product::get();
        $data['gallerys'] = \App\Models\Gallery::get();
        $data['testimonials'] = \App\Models\Testimonial::get();
        $data['offerDiscount'] = \App\Models\OfferDiscount::first();
        $data['videos'] = \App\Models\Video::get();
        $data['submitURL'] = route('home');
        $data['categories'] = \App\Models\ProductCategory::inRandomOrder()->take(4)->get();
        $data['title'] = 'Home';
        return view('frontend.welcome', $data);
    }
    function about(Request $request) 
    {
        $data['gallerys'] = \App\Models\Gallery::get();
        $data['testimonials'] = \App\Models\Testimonial::get();
        $data['frequentlyAskedQuestions'] = \App\Models\FrequentlyAskedQuestion::get();
        $data['videos'] = \App\Models\Video::get();
        $data['title'] = 'About Us';
        return view('frontend.about', $data);
    }
    function contact(Request $request) 
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "name"      =>"required|string",
                "email"     =>"required|email",
                "message"   =>"required|string",
            ]);

            if ($validator->fails()) {
                return response()->json(['type'=> 'error', 'message'=> $validator->errors()->all()]);
            }

            $panelsettings = \Illuminate\Support\Facades\DB::table('panelsettings')->first();
            if (!blank($panelsettings)) {
                if (!empty($panelsettings->notification_email)) {
                    \Illuminate\Support\Facades\Mail::to($panelsettings->notification_email)->send(new \App\Mail\ContactUs($request)  );
                }
            }

            return response()->json(['type'=> 'success', 'message'=> 'Successfully submit your request']);
        }
        $data['title'] = 'Contact Us';
        $data['submitURL'] = route('contact');
        $data['contactusImage'] = \App\Models\ContactusImage::first();
        return view('frontend.contact', $data);
    }
    function testimonialEnquiry(Request $request)
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
            return response()->json(['type'=>'success', 'message'=> "Successfully submit your enquiry"]);
            
        }
        $data['title'] = 'Testimonial Enquiry';
        $data['submitURL'] = route('testimonialenquiry');
        return view('frontend.testimonialenquiry', $data);
    }

    function menu(Request $request) 
    {
        $data['allProducts'] = \App\Models\Product::get();
        $data['title'] = 'Menu';
        return view('frontend.menu', $data);
    }

    function faq(Request $request) 
    {
        $data['frequentlyAskedQuestions'] = \App\Models\FrequentlyAskedQuestion::get();
        $data['title'] = 'FAQ';
        return view('frontend.faq', $data);
    }
}
