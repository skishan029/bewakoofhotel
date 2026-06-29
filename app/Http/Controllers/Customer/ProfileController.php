<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class ProfileController extends Controller
{
    public function update(Request $request)
    {

        $user =  Customer::find(Auth::guard('customer')->user()->id);

        if ($request->isMethod('post')) {
            $request->validate([
                'name'      => 'required|string|max:255',
                'mobile'    => 'required|numeric|digits:10|unique:customers,username,' . $user->id,
                'address'   => 'nullable|string|max:500',
                'email'     => 'nullable|email',
                'region_id'     => 'required|exists:regions,id',
                'subregion_id'  => 'required|exists:regions,id',
            ]);

            try {
                $user->name     = strip_tags(trim($request->name));
                $user->username = strip_tags(trim($request->mobile));
                $user->address  = strip_tags(trim($request->address));
                $user->landmark = strip_tags(trim($request->landmark));
                $user->email    = empty($request->email) ? null : strip_tags(trim($request->email));
                $user->region_id     = strip_tags(trim($request->region_id));
                $user->subregion_id  = strip_tags(trim($request->subregion_id));

                $user->save();

                return redirect()->back()->with('success', 'Profile updated successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.');
            }
        }
        $data['parentRegions'] = \App\Models\Region::whereNull('parent_id')->has('subregion')->get(['id', 'name']);
        $data['subRegions'] = \App\Models\Region::where('parent_id', $user->region_id)->whereNotNull('parent_id')->get(['id', 'name']);
        $data['title'] = 'My Profile';
        return view('customer.profile.update', $data);
    }

    public function changePassword(Request $request)
    {
        $user = Customer::find(Auth::guard('customer')->user()->id);

        if ($request->isMethod('post')) {
            $request->validate([
                'new_password' => 'required|string|min:6|confirmed',
            ], [
                'new_password.confirmed' => 'The confirm password field does not match.',
            ]);

            try {
                $user->password = Hash::make(trim($request->new_password));
                $user->save();

                return redirect()->back()->with('success', 'Password changed successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Something went wrong. Please try again.');
            }
        }
        $data['title'] = 'Change Password';
        return view('customer.profile.change-password', $data);
    }
}
