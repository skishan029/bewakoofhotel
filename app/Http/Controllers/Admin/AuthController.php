<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function index(Request $request)
    {

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "email"     => "required|email",
                "password"  => "required"
            ]);

            if ($validator->fails()) {
                $output = ['type' => "error", "message" => $validator->errors()->all()];
            } else {
                $credentials = [
                    'password'  => trim($request->password),
                    'email'     => trim($request->email),
                ];

                $user = \App\Models\User::where([
                    ['email', $request->email]
                ])->first();
                if (blank($user)) {
                    return response()->json(['type' => 'error', 'message' => 'provided email do not match our records.']);
                }

                if ($user->user_type == 1) {
                    if (Auth::guard('admin')->attempt($credentials)) {
                        $request->session()->regenerate();
                        $output = ['type' => 'success', 'message' => 'Successfully loggedin', 'usertype' => '1', 'url' => route('admin.dashboard')];
                    } else {
                        $output = ['type' => 'error', 'message' => 'The provided password do not match our records.'];
                    }
                } else {
                    if (!Hash::check($request->password, $user->password)) {
                        return response()->json(['type' => 'error', 'message' => 'Login Fail, pls check password']);
                    }

                    $user->otp          = substr(str_shuffle("123456789123456789123456789123456789"), 0, 6);
                    $user->otp_key      = md5($request->email . $user->otp . time());
                    $user->save();

                    $sendEmail = $user->email;
                    $panelsettings = \Illuminate\Support\Facades\DB::table('panelsettings')->first();
                    if (!blank($panelsettings)) {
                        if (!empty($panelsettings->notification_email)) {
                            $sendEmail = $panelsettings->notification_email;
                        }
                    }
                    \Illuminate\Support\Facades\Mail::to($sendEmail)->send( new \App\Mail\LoginOtp($user));

                    $maskEmail = \Illuminate\Support\Str::mask($sendEmail, '*', 15);
                    $output = ['type' => 'success', 'message' => 'Successfully send OTP on ' . $maskEmail, 'usertype' => '2', 'url' => route('admin.verify', ['otp_key' => $user->otp_key])];
                }
            }
            return response()->json($output);
        }

        return view('admin.auth.login');
    }

    public function verifyLoginOtp(Request $request, $otp_key)
    {
        $user = \App\Models\User::where('otp_key', $otp_key)->first();

        if ($request->ajax()) {
            $validator = validator($request->all(), [
                "otp" => "required|regex:/^[0-9]{6}$/",
            ]);

            if ($validator->fails()) {
                $output = ['type' => 'error', 'message' => $validator->errors()->all()];
            } else {
                if (blank($user)) {
                    return response()->json(['type' => 'error', 'message' => 'Invalid otp key']);
                }
                if (trim($request->otp) !== $user->otp) {
                    return response()->json(['type' => 'error', 'message' => 'Invalid otp']);
                }

                $userLogin = \App\Models\User::where([
                    ['otp_key', '=', $otp_key],
                    ['otp', '=', $request->otp]
                ])->first();
                Auth::guard('admin')->login($userLogin, true);
                if (Auth::guard('admin')->check()) {
                    $output = ['type' => 'success', 'message' => 'Successfully loggedin', 'url' => route('admin.dashboard')];
                }
            }
            return response()->json($output);
        }

        if (blank($user)) {
            return redirect()->route('admin.index');
        }

        $data['user'] = $user;
        return view('admin.auth.loginotpverify', $data);
    }
    function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.index')->with('admin_signin', ['You have been logged out successfully!', 'success']);
        } else {
            return redirect()->route('admin.index');
        }
    }
}
