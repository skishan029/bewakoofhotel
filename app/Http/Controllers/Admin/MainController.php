<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    function dashboard(Request $request)
    {

        if (!empty($request->start_date)) {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            // $productOrderItem = \App\Models\ProductOrderItem::with(['product'])->whereDate('created_at', '=', $start_date)->groupBy('product_id')->get();
            $productOrderItem = \App\Models\Product::get();
        } else {
            $start_date = date('Y-m-d');
            $productOrderItem = \App\Models\Product::get();
        }



        $data['title'] = 'Today Order List';
        $data['productOrderItem'] = $productOrderItem;
        $data['start_date'] = $start_date;

        $data['todayOrders'] = \App\Models\ProductOrder::whereDate('order_date', date('Y-m-d'))->count();
        $data['totalInvoice'] = \App\Models\ProductOrder::count();
        return view('admin.site.dashboard', $data);
    }
    public function changePassword(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'password'          => 'required|min:6',
                'confirmPassword'   => 'required_with:password|same:password',
            ]);
            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                $user = \App\Models\User::find(Auth::guard('admin')->id());

                $user->password = \Illuminate\Support\Facades\Hash::make(trim($request->password));
                $user->save();
                $output = ['type' => 'success', 'message' => 'Successfully Changed Password'];
            }
            return response()->json($output);
        }
        $data['submitURL'] = route('admin.profile.changepassword');

        return view('admin.profile.changepassword', $data);
    }

    public function empChangePassword(Request $request)
    {

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                'emp_id'          => 'required|',
                'password'          => 'required|min:6',
                'confirmPassword'   => 'required_with:password|same:password',
            ]);
            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                $user = \App\Models\User::find($request->emp_id);

                $user->password = \Illuminate\Support\Facades\Hash::make(trim($request->password));
                $user->save();
                $output = ['type' => 'success', 'message' => 'Successfully Changed Password'];
            }
            return response()->json($output);
        }

        $data['submitURL'] = route('admin.profile.empchangepassword');
        return view('admin.profile.empchangepassword', $data);
    }

    public function empPassword(Request $request)
    {
        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "email" => "required|email",
            ]);
            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {


                $user = \App\Models\User::where('email', $request->email)->first();
                if (blank($user)) {
                    $output = ['type' => 'error', 'message' => 'Invalid Email id'];
                    return response()->json($output);
                }

                $html = view('admin.profile.empchangepassfrm', ['user' => $user])->render();
                $output = ['type' => 'success', 'message' => 'Successfully fetch', 'html' => $html];
            }
            return response()->json($output);
        }
    }



    function panelSetting(Request $request)
    {
        $panelsettings = \Illuminate\Support\Facades\DB::table('panelsettings')->first();
        if ($request->ajax()) {
            $validator =  validator($request->all(), [
                "notification_email" => "nullable|string",
                "email"             => "nullable|string",
                "contact_one"       => "nullable|string",
                "contact_two"       => "nullable|string",
                "banner_heading"    => "nullable|string",
                "address"           => "nullable|string",
                "facebook"          => "nullable|string",
                "youtube"           => "nullable|string",
                "instagram"         => "nullable|string",
                "twitter"           => "nullable|string",
                "established_year"  => "nullable|string",
                "about_content"     => "nullable|string",
                "owner_name"        => "nullable|string",
                "owner_about"       => "nullable|string",
                "company_logo"      => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "favicon_logo"      => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "left_image"        => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "right_image"       => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "user_image"        => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "user_image2"       => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "frequent_image_1"        => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "frequent_image_2"       => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "banner_image"       => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "pop_back_image"       => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "footer_back_image"       => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "testimonial_back_image"       => "nullable|max:2048|mimes:png,jpeg,jpg,webp",
                "restaurant_open_time"         => "required",
                "restaurant_close_time"        => "required",
                "is_restaurant_open"           => "required|boolean",
            ]);
            if ($validator->fails()) {
                return response()->json(['type' => 'error', 'message' => $validator->errors()->all()]);
            }

            if (blank($panelsettings)) {

                $company_logo = NULL;
                if (isset($_FILES['company_logo'])) {
                    if ($_FILES['company_logo']['error'] == 0) {
                        $company_logo = strip_tags($request->file('company_logo')->store('logo'));
                    }
                }
                $favicon_logo = NULL;
                if (isset($_FILES['favicon_logo'])) {
                    if ($_FILES['favicon_logo']['error'] == 0) {
                        $favicon_logo = strip_tags($request->file('favicon_logo')->store('logo'));
                    }
                }


                $left_image = NULL;
                if (isset($_FILES['left_image'])) {
                    if ($_FILES['left_image']['error'] == 0) {
                        $left_image = strip_tags($request->file('left_image')->store('panelimage'));
                    }
                }
                $right_image = NULL;
                if (isset($_FILES['right_image'])) {
                    if ($_FILES['right_image']['error'] == 0) {
                        $right_image = strip_tags($request->file('right_image')->store('panelimage'));
                    }
                }

                /*
                $user_image = NULL;
                if (isset($_FILES['user_image'])) {
                    if($_FILES['user_image']['error'] == 0){
                        $user_image = strip_tags($request->file('user_image')->store('panelimage'));
                    }
                }
                $user_image2 = NULL;
                if (isset($_FILES['user_image2'])) {
                    if($_FILES['user_image2']['error'] == 0){
                        $user_image2 = strip_tags($request->file('user_image2')->store('panelimage'));
                    }
                }
                */

                $frequent_image_1 = NULL;
                if (isset($_FILES['frequent_image_1'])) {
                    if ($_FILES['frequent_image_1']['error'] == 0) {
                        $frequent_image_1 = strip_tags($request->file('frequent_image_1')->store('panelimage'));
                    }
                }
                $frequent_image_2 = NULL;
                if (isset($_FILES['frequent_image_2'])) {
                    if ($_FILES['frequent_image_2']['error'] == 0) {
                        $frequent_image_2 = strip_tags($request->file('frequent_image_2')->store('panelimage'));
                    }
                }
                $banner_image = NULL;
                if (isset($_FILES['banner_image'])) {
                    if ($_FILES['banner_image']['error'] == 0) {
                        $banner_image = strip_tags($request->file('banner_image')->store('panelimage'));
                    }
                }
                $pop_back_image = NULL;
                if (isset($_FILES['pop_back_image'])) {
                    if ($_FILES['pop_back_image']['error'] == 0) {
                        $pop_back_image = strip_tags($request->file('pop_back_image')->store('panelimage'));
                    }
                }
                $footer_back_image = NULL;
                if (isset($_FILES['footer_back_image'])) {
                    if ($_FILES['footer_back_image']['error'] == 0) {
                        $footer_back_image = strip_tags($request->file('footer_back_image')->store('panelimage'));
                    }
                }
                $testimonial_back_image = NULL;
                if (isset($_FILES['testimonial_back_image'])) {
                    if ($_FILES['testimonial_back_image']['error'] == 0) {
                        $testimonial_back_image = strip_tags($request->file('testimonial_back_image')->store('panelimage'));
                    }
                }



                \Illuminate\Support\Facades\DB::table('panelsettings')->insert([
                    'notification_email'    => (empty($request->notification_email)) ? NULL : strip_tags(trim($request->notification_email)),
                    'email'                 => (empty($request->email)) ? NULL : strip_tags(trim($request->email)),
                    'contact_one'           => (empty($request->contact_one)) ? NULL : strip_tags(trim($request->contact_one)),
                    'contact_two'           => (empty($request->contact_two)) ? NULL : strip_tags(trim($request->contact_two)),
                    'address'               => (empty($request->address)) ? NULL : strip_tags(trim($request->address)),
                    'banner_heading'        => (empty($request->banner_heading)) ? NULL : strip_tags(trim($request->banner_heading)),
                    'facebook'              => (empty($request->facebook)) ? NULL : strip_tags(trim($request->facebook)),
                    'youtube'               => (empty($request->youtube)) ? NULL : strip_tags(trim($request->youtube)),
                    'instagram'             => (empty($request->instagram)) ? NULL : strip_tags(trim($request->instagram)),
                    'twitter'               => (empty($request->twitter)) ? NULL : strip_tags(trim($request->twitter)),
                    'established_year'      => (empty($request->established_year)) ? NULL : strip_tags(trim($request->established_year)),
                    'about_content'         => (empty($request->about_content)) ? NULL : $request->about_content,
                    'owner_name'            => (empty($request->owner_name)) ? NULL : strip_tags(trim($request->owner_name)),
                    'owner_about'           => (empty($request->owner_about)) ? NULL : strip_tags(trim($request->owner_about)),
                    'company_logo'          => (empty($company_logo)) ? NULL : strip_tags(trim($company_logo)),
                    'favicon_logo'          => (empty($favicon_logo)) ? NULL : strip_tags(trim($favicon_logo)),
                    'left_image'            => (empty($left_image)) ? NULL : strip_tags(trim($left_image)),
                    'right_image'           => (empty($right_image)) ? NULL : strip_tags(trim($right_image)),
                    //'user_image'            => (empty($user_image)) ? NULL : strip_tags(trim($user_image)),
                    //'user_image2'           => (empty($user_image2)) ? NULL : strip_tags(trim($user_image2)),
                    'frequent_image_1'      => (empty($frequent_image_1)) ? NULL : strip_tags(trim($frequent_image_1)),
                    'frequent_image_2'      => (empty($frequent_image_2)) ? NULL : strip_tags(trim($frequent_image_2)),
                    'banner_image'          => (empty($banner_image)) ? NULL : strip_tags(trim($banner_image)),
                    'pop_back_image'      => (empty($pop_back_image)) ? NULL : strip_tags(trim($pop_back_image)),
                    'footer_back_image'          => (empty($footer_back_image)) ? NULL : strip_tags(trim($footer_back_image)),
                    'testimonial_back_image'          => (empty($testimonial_back_image)) ? NULL : strip_tags(trim($testimonial_back_image)),
                    'restaurant_open_time'            => (empty($request->restaurant_open_time)) ? '09:00:00' : $request->restaurant_open_time,
                    'restaurant_close_time'           => (empty($request->restaurant_close_time)) ? '22:00:00' : $request->restaurant_close_time,
                    'is_restaurant_open'              => (isset($request->is_restaurant_open)) ? $request->is_restaurant_open : 1,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            } else {

                $company_logo = $panelsettings->company_logo;
                if (isset($_FILES['company_logo'])) {
                    if ($_FILES['company_logo']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->company_logo);
                        $company_logo = strip_tags($request->file('company_logo')->store('logo'));
                    }
                }
                $favicon_logo = $panelsettings->favicon_logo;
                if (isset($_FILES['favicon_logo'])) {
                    if ($_FILES['favicon_logo']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->favicon_logo);
                        $favicon_logo = strip_tags($request->file('favicon_logo')->store('logo'));
                    }
                }

                $left_image = $panelsettings->left_image;
                if (isset($_FILES['left_image'])) {
                    if ($_FILES['left_image']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->left_image);
                        $left_image = strip_tags($request->file('left_image')->store('panelimage'));
                    }
                }
                $right_image = $panelsettings->right_image;
                if (isset($_FILES['right_image'])) {
                    if ($_FILES['right_image']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->right_image);
                        $right_image = strip_tags($request->file('right_image')->store('panelimage'));
                    }
                }

                /*
                $user_image = $panelsettings->user_image;
                if (isset($_FILES['user_image'])) {
                    if($_FILES['user_image']['error'] == 0){
                        \App\Helper\Helper::customFileDelete($panelsettings->user_image);
                        $user_image = strip_tags($request->file('user_image')->store('panelimage'));
                    }
                }
                $user_image2 = $panelsettings->user_image2;
                if (isset($_FILES['user_image2'])) {
                    if($_FILES['user_image2']['error'] == 0){
                        \App\Helper\Helper::customFileDelete($panelsettings->user_image2);
                        $user_image2 = strip_tags($request->file('user_image2')->store('panelimage'));
                    }
                }
                */

                $frequent_image_1 = $panelsettings->frequent_image_1;
                if (isset($_FILES['frequent_image_1'])) {
                    if ($_FILES['frequent_image_1']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->frequent_image_1);
                        $frequent_image_1 = strip_tags($request->file('frequent_image_1')->store('panelimage'));
                    }
                }
                $frequent_image_2 = $panelsettings->frequent_image_2;
                if (isset($_FILES['frequent_image_2'])) {
                    if ($_FILES['frequent_image_2']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->frequent_image_2);
                        $frequent_image_2 = strip_tags($request->file('frequent_image_2')->store('panelimage'));
                    }
                }
                $banner_image = $panelsettings->banner_image;
                if (isset($_FILES['banner_image'])) {
                    if ($_FILES['banner_image']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->banner_image);
                        $banner_image = strip_tags($request->file('banner_image')->store('panelimage'));
                    }
                }

                $pop_back_image = $panelsettings->pop_back_image;
                if (isset($_FILES['pop_back_image'])) {
                    if ($_FILES['pop_back_image']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->pop_back_image);
                        $pop_back_image = strip_tags($request->file('pop_back_image')->store('panelimage'));
                    }
                }
                $footer_back_image = $panelsettings->footer_back_image;
                if (isset($_FILES['footer_back_image'])) {
                    if ($_FILES['footer_back_image']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->banner_image);
                        $footer_back_image = strip_tags($request->file('footer_back_image')->store('panelimage'));
                    }
                }
                $testimonial_back_image = $panelsettings->testimonial_back_image;
                if (isset($_FILES['testimonial_back_image'])) {
                    if ($_FILES['testimonial_back_image']['error'] == 0) {
                        \App\Helper\Helper::customFileDelete($panelsettings->banner_image);
                        $testimonial_back_image = strip_tags($request->file('testimonial_back_image')->store('panelimage'));
                    }
                }


                \Illuminate\Support\Facades\DB::table('panelsettings')->where('id', $panelsettings->id)->update([
                    'notification_email'    => (empty($request->notification_email)) ? NULL : strip_tags(trim($request->notification_email)),
                    'email'                 => (empty($request->email)) ? NULL : strip_tags(trim($request->email)),
                    'contact_one'           => (empty($request->contact_one)) ? NULL : strip_tags(trim($request->contact_one)),
                    'contact_two'           => (empty($request->contact_two)) ? NULL : strip_tags(trim($request->contact_two)),
                    'address'               => (empty($request->address)) ? NULL : strip_tags(trim($request->address)),
                    'banner_heading'        => (empty($request->banner_heading)) ? NULL : strip_tags(trim($request->banner_heading)),
                    'facebook'              => (empty($request->facebook)) ? NULL : strip_tags(trim($request->facebook)),
                    'youtube'               => (empty($request->youtube)) ? NULL : strip_tags(trim($request->youtube)),
                    'instagram'             => (empty($request->instagram)) ? NULL : strip_tags(trim($request->instagram)),
                    'twitter'               => (empty($request->twitter)) ? NULL : strip_tags(trim($request->twitter)),
                    'established_year'      => (empty($request->established_year)) ? NULL : strip_tags(trim($request->established_year)),
                    'about_content'         => (empty($request->about_content)) ? NULL : $request->about_content,
                    'owner_name'            => (empty($request->owner_name)) ? NULL : strip_tags(trim($request->owner_name)),
                    'owner_about'           => (empty($request->owner_about)) ? NULL : strip_tags(trim($request->owner_about)),
                    'company_logo'          => (empty($company_logo)) ? NULL : strip_tags(trim($company_logo)),
                    'favicon_logo'          => (empty($favicon_logo)) ? NULL : strip_tags(trim($favicon_logo)),
                    'left_image'            => (empty($left_image)) ? NULL : strip_tags(trim($left_image)),
                    'right_image'           => (empty($right_image)) ? NULL : strip_tags(trim($right_image)),
                    //'user_image'            => (empty($user_image)) ? NULL : strip_tags(trim($user_image)),
                    //'user_image2'           => (empty($user_image2)) ? NULL : strip_tags(trim($user_image2)),
                    'frequent_image_1'      => (empty($frequent_image_1)) ? NULL : strip_tags(trim($frequent_image_1)),
                    'frequent_image_2'      => (empty($frequent_image_2)) ? NULL : strip_tags(trim($frequent_image_2)),
                    'banner_image'          => (empty($banner_image)) ? NULL : strip_tags(trim($banner_image)),
                    'pop_back_image'      => (empty($pop_back_image)) ? NULL : strip_tags(trim($pop_back_image)),
                    'footer_back_image'          => (empty($footer_back_image)) ? NULL : strip_tags(trim($footer_back_image)),
                    'testimonial_back_image'          => (empty($testimonial_back_image)) ? NULL : strip_tags(trim($testimonial_back_image)),
                    'restaurant_open_time'            => (empty($request->restaurant_open_time)) ? '09:00:00' : $request->restaurant_open_time,
                    'restaurant_close_time'           => (empty($request->restaurant_close_time)) ? '22:00:00' : $request->restaurant_close_time,
                    'is_restaurant_open'              => (isset($request->is_restaurant_open)) ? $request->is_restaurant_open : 1,
                    'updated_at'            => now(),
                ]);
            }
            return response()->json(['type' => 'success', 'message' => "Successfully update setting"]);
        }
        $data['title'] = 'Setting';
        $data['submitURL'] = route('admin.frontend.setting');
        $data['panelsettings'] = $panelsettings;
        return view('admin.frontend.setting', $data);
    }
}
