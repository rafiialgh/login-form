<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register()
    {
        $data['title'] = 'Register';
        return view('user/register', $data);
    }

    public function register_action(Request $request, )
    {
        $request->validate(
            [
                'email' => 'required',
                'username' => 'required|unique:tb_user',
                'password' => [
                    'required',
                    'string',
                    'min:10',
                    // must be at least 10 characters in length
                    'regex:/[a-z]/',
                    // must contain at least one lowercase letter
                    'regex:/[A-Z]/',
                    // must contain at least one uppercase letter
                    'regex:/[0-9]/',
                    // must contain at least one digit
                    'regex:/[@$!%*#?&]/',
                    // must contain a special character
                ],
                'password_confirm' => 'required|same:password',

            ],
            [
                'password.regex' => 'Password must contain at least one uppercase, lowercase, one number, and one special character',
                'password.min' => 'Password must be at least 10 characters',
                'password_confirm.same' => 'Password confirmation must be the same as password',
            ]
        );

        $user = new User([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        return redirect()->route('login')->with('success', 'Registration success. Please login!');
    }


    public function login()
    {
        $data['title'] = 'Login';
        return view('user/login', $data);
    }

    public function login_action(Request $request, CaptchaServiceController $captchaServiceController)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ]);

        if (RateLimiter::tooManyAttempts('send-message:' . $request->username, $perMinute = 3)) {
            $seconds = RateLimiter::availableIn('send-message:' . $request->username);

            for ($i = 0; $i < $seconds; $i++) {
                RateLimiter::hit('send-message:' . $request->username);
            }
            return 'You may try again in ' . $seconds . ' seconds.';
        }

        RateLimiter::hit('send-message:' . $request->username);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with(['captcha' => $captchaServiceController->reloadCaptcha()]);
        }

        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
    }

    public function password()
    {
        $data['title'] = 'Change Password';
        return view('user/password', $data);
    }

    public function password_action(Request $request)
    {
        $request->validate(
            [
                'old_password' => 'required|current_password',
                'new_password' => [
                    'required',
                    'string',
                    'min:10',
                    // must be at least 10 characters in length
                    'regex:/[a-z]/',
                    // must contain at least one lowercase letter
                    'regex:/[A-Z]/',
                    // must contain at least one uppercase letter
                    'regex:/[0-9]/',
                    // must contain at least one digit
                    'regex:/[@$!%*#?&]/',
                    // must contain a special character
                ],
            ],
            [
                'new_password.regex' => 'Password must contain at least one uppercase, lowercase, one number, and one special character',
                'new_password.min' => 'Password must be at least 10 characters',
                'new_password_confirmation.same' => 'Password confirmation must be the same as password',
            ]
        );

        $user = User::find(Auth::id());
        $user->password = Hash::make($request->new_password);
        $user->save();
        $request->session()->regenerate();
        return redirect('/')->with('success', 'Password changed!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function forgot_password()
    {
        $data['title'] = 'Forgot your password?';
        return view('user/forgot-password', $data);
    }

    public function password_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function reset_password(Request $request, $token)
    {
        $data['title'] = 'Reset your password';
        return view('user/reset-password', $data)->with(
            ['token' => $token]
        );
    }

    public function reset_password_action(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:10',
                // must be at least 10 characters in length
                'regex:/[a-z]/',
                // must contain at least one lowercase letter
                'regex:/[A-Z]/',
                // must contain at least one uppercase letter
                'regex:/[0-9]/',
                // must contain at least one digit
                'regex:/[@$!%*#?&]/',
                // must contain a special character
            ],
            'password_confirmation' => 'required|same:password',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                // Auth::login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

}