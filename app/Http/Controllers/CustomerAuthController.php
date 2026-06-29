<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exceptions\LogicException;
use App\Mail\SendPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerAuthController extends Controller
{
    use \App\Traits\ApiTrait;

    public function login(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'username' => ['required', Rule::exists('customers', 'username')->where(fn($query) => $query->where('is_verified', true)->where('is_active', true))],
                'password'  => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError($validator->errors()->first());
            }

            try {
                $customer = Customer::where('username', $request->username)->firstOrFail();

                if (!Hash::check(trim($request->password), $customer->password)) {
                    throw new LogicException("Invalid password");
                }

                Auth::guard('customer')->login($customer);
                /*
                $otp = rand(100000, 999999);
                $otp_key = $otp . time() . "#login";

                $encoded = rtrim(strtr(base64_encode($otp_key), '+/', '-_'), '=');

                $customer->update([
                    'otp'               => $otp,
                    'otp_expires_at'    => now()->addMinutes(15),
                    'otp_key'           => $encoded,
                ]);
                */

                return $this->apiSuccess('Logged in successfully', ['url' => route('home')]);

                //return $this->apiSuccess('OTP sent successfully', ['url' => route('customer.verify', ['key' => $encoded])]);
            } catch (LogicException $e) {
                return $this->apiError($e->getMessage());
            } catch (ModelNotFoundException $e) {
                return $this->apiError("Customer not found");
            } catch (\Exception $e) {
                return $this->apiError("Something went wrong");
            }
        }
        $data['title'] = 'Login';
        return view('frontend.auth.login', $data);
    }

    public function register(Request $request)
    {

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name'      => 'required|string|max:255',
                'email'     => ['required', 'email'],
                'password'  => 'required|min:6',
                'confirm_password'=> 'required|min:6|same:password',
                'username'  => ['required', 'string', Rule::unique('customers', 'username')->where(fn($query) => $query->where('is_verified', true))],
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError($validator->errors()->first());
            }

            try {
                
                $otp = rand(100000, 999999);
                $otp_key = $otp . time() . "#register";

                $encoded = rtrim(strtr(base64_encode($otp_key), '+/', '-_'), '=');
                
                
                $customer = Customer::updateOrCreate([
                    'username'      => $request->username,
                ], [
                    'name'              => $request->name,
                    'password'          => Hash::make(trim($request->password)),
                    'username'          => $request->username,
                    'email'             => $request->email,
                    'otp_key'           => $encoded,
                    'otp'               => $otp,
                    'otp_expires_at'    => now()->addMinutes(15),
                    'is_verified'       => true,
                    'is_active'         => true,
                ]);

                //return $this->apiSuccess('OTP sent successfully, please verify your account', ['url' => route('customer.verify', ['key' => $encoded])]);

                Auth::guard('customer')->login($customer);

                return $this->apiSuccess('Register successfully', ['url' => route('customer.dashboard')]);
                
            } catch (\Exception $e) {
                return $this->apiError("Something went wrong");
            }
        }
        $data['title'] = 'Register';
        return view('frontend.auth.register', $data);
    }

    public function verify(Request $request, $key)
    {
        $customer = Customer::where('otp_key', $key)->firstOrFail();

        $decode = base64_decode(strtr($customer->otp_key, '-_', '+/'));
        $decodeArr = explode("#", $decode);
        $verifyType = $decodeArr[1] ?? 'login';

        return view('frontend.auth.verify', compact('customer', 'verifyType'));
    }

    public function verifyOtp(Request $request)
    {

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'key' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError($validator->errors()->first());
            }

            try {
                $customer = Customer::where('otp_key', $request->key)->firstOrFail();

                // ✅ Expired OTP
                if ($customer->otp_expires_at < now()) {
                    throw new LogicException("OTP expired, please request a new one");
                }

                // ✅ Invalid OTP
                if ($customer->otp != $request->otp) {
                    throw new LogicException("Invalid OTP");
                }

                $decode = base64_decode(strtr($customer->otp_key, '-_', '+/'));
                $decodeArr = explode("#", $decode);
                $verifyType = $decodeArr[1] ?? 'login';

                $updateArr = [
                    'otp'               => null,
                    'otp_expires_at'    => null,
                    'otp_key'           => null,
                ];

                $message = "Logged in successfully";
                if ($verifyType == 'register') {
                    $updateArr = array_merge($updateArr, [
                        'is_verified'       => true,
                        'is_active'         => true,
                    ]);
                    $message = "Your account has been verified successfully";
                } else {
                    throw_if($customer->is_verified == false, LogicException::class, "Account not verified");
                    throw_if($customer->is_active == false, LogicException::class, "Your account is not active");
                }
                $customer->update($updateArr);

                Auth::guard('customer')->login($customer);
                return $this->apiSuccess($message, ['url' => route('customer.dashboard')]);
            } catch (LogicException $e) {
                return $this->apiError($e->getMessage());
            } catch (ModelNotFoundException $e) {
                return $this->apiError("Customer not found");
            } catch (\Exception $e) {
                return $this->apiError("Something went wrong");
            }
        }
    }

    public function resendOtp(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'key' => 'required|string|exists:customers,otp_key',
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError($validator->errors()->first());
            }

            try {
                $customer = Customer::where('otp_key', $request->key)->firstOrFail();
                $otp = rand(100000, 999999);

                $customer->update([
                    'otp'               => $otp,
                    'otp_expires_at'    => now()->addMinutes(15),
                ]);

                return $this->apiSuccess('New OTP sent successfully');
            } catch (LogicException $e) {
                return $this->apiError($e->getMessage());
            } catch (ModelNotFoundException $e) {
                return $this->apiError("OTP key not found");
            } catch (\Exception $e) {
                return $this->apiError("Something went wrong");
            }
        }
    }

    public function logout()
    {
        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
            return redirect()->route('customer.login')->with('customer_signin', ['You have been logged out successfully!', 'success']);
        } else {
            return redirect()->route('customer.login');
        }
    }

    public function changePassword(Request $request)  
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'username' => ['required', Rule::exists('customers', 'username')->where(fn($query) => $query->where('is_verified', true)->where('is_active', true))],
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError($validator->errors()->first());
            }

            try {
                $customer   = Customer::where('username', $request->username)->firstOrFail();
                $password   = substr(str_shuffle('1234567890AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz'), 0, 8);
                throw_if(empty($customer->email), LogicException::class, "Email not found");
                
                // Save hashed password
                $customer->password = Hash::make($password);
                $customer->save();

                // Send email
                Mail::to($customer->email)->send(
                    new SendPasswordMail($customer, $password)
                );

                return $this->apiSuccess('Password will be send to your email');
            } catch (LogicException $e) {
                return $this->apiError($e->getMessage());
            } catch (ModelNotFoundException $e) {
                return $this->apiError("Customer not found");
            } catch (\Exception $e) {
                return $this->apiError("Something went wrong", [$e->getMessage()]);
            }
        }
        $data['title'] = 'Forget Password';
        return view('frontend.auth.change-password', $data);
    }
}
