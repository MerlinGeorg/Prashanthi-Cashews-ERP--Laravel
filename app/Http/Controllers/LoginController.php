<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\PackageCenter;
use App\Models\PasswordReset;
use App\Models\Stockyard;
use App\Models\User;
use Auth;
use Config;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Mail;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email_or_username' => 'required|max:100',
            'password' => 'required',
        ]);

        $username = $request->email_or_username;

        if (filter_var($request->email_or_username, FILTER_VALIDATE_EMAIL)) {
            Auth::attempt(['email' => $request->email_or_username, 'password' => $request->password], $request->remember);
        } else {
            Auth::attempt(['username' => $request->email_or_username, 'password' => $request->password], $request->remember);
        }

        if (Auth::check()) {
            Factory::updateList();
            Stockyard::updateList();
            PackageCenter::updateList();

            Session::flash('message', 'You have successfully logged in to ' . Config::get('app.name'));
            return redirect()->intended('admin');
        }

        return back()->withInput()->with('error', 'Incorrect username or password');
    }

    public function showForgotForm()
    {
        return view('admin.auth.forgot');
    }

    public function forgot(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {

            // user found
            $token = md5(time() . $user->username . $user->email);

            PasswordReset::where('email', $request->email)->delete();
            PasswordReset::create([
                'email' => $user->email,
                'token' => $token,
            ]);

            $subject = "Reset Password Request";
            $msg = "Please click this link : " . '<a href="' . route('admin.reset', $token) . '">' . route('admin.reset', $token) . '</a>' . ' to change your password.';

            $data = [
                'to' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'subject' => $subject,
                'message' => $msg,
            ];

            Mail::send('emails.reset-password', ['data' => $data], function ($m) use ($data) {
                $m->to($data['to'], $data['name'])->subject($data['subject']);
            });

            return back()->with('msg', 'Verification Link Sent Successfully!. Please Check your email.');
        } else {
            // user not found
            return back()->withInput()->with('error', 'No account found with this email.');
        }
    }

    public function showResetForm(Request $request, $token)
    {
        return view('admin.auth.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $reset_password = PasswordReset::where(['email' => $request->email, 'token' => $request->token]);

            if ($reset_password->count()) {

                $user->password = \Hash::make($request->password);
                if ($user->save()) {
                    PasswordReset::where('email', $request->email)->delete();

                    return redirect('admin/login')->withSuccess('Password updated!');
                } else {

                    return back()->withInput()->with('error', 'Oops! Reset password failed.');
                }
            } else {
                // token not found
                return back()->withInput()->with('error', 'Invalid token or email. Please try agian.');
            }
        } else {

            // user not found
            return back()->withInput()->with('error', 'No account found with this email.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('admin/login');
    }
}