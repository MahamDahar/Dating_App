<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdminNotification;
use App\Support\CountryCities;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Show signup form
    public function show()
    {
        return view('frontend.register');
    }

    // Handle form submission
    public function register(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'username'       => 'required|unique:users,username|max:255',
            'name'           => 'required|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6|confirmed',
            'gender'         => 'required|in:Man,Woman',
            'looking_for'    => 'required|in:Man,Woman',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'country'        => ['required', 'string', 'max:255', Rule::in(CountryCities::countries())],
        ]);

        // 2. Save to database
        $user = User::create([
            'username'       => $request->username,
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'user',
            'gender'         => $request->gender,
            'looking_for'    => $request->looking_for,
            'marital_status' => $request->marital_status,
            'country'        => $request->country,
        ]);

        $this->issueEmailOtp($user);

        // 3. Admin ko notification bhejo
        AdminNotification::newUser($user);

        // 4. Redirect to OTP verification screen
        return redirect()
            ->route('verification.otp.show')
            ->with('verification_email', $user->email)
            ->with('success', 'Account created. We sent an OTP to your email for verification.');
    }

    // Show the login form
    public function loginForm()
    {
        return view('frontend.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (auth()->user()->role === 'user' && ! auth()->user()->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('verification.otp.show')
                    ->with('verification_email', $credentials['email'])
                    ->with('error', 'Please verify your email with OTP before login.');
            }

            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                                 ->with('success', 'Welcome Admin!');
            }

            return redirect()->route('user.discover')
                             ->with('success', 'Login successful!');
        }

        return back()->with('error', 'Invalid email or password');
    }

    public function showForgotPasswordForm()
    {
        return view('frontend.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link has been sent to your email.');
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Please wait a moment, then try again.');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', __($status));
    }

    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('frontend.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password has been reset successfully. Please sign in.');
        }

        return back()->withInput($request->only('email'))->with('error', __($status));
    }

    public function showOtpVerificationForm(Request $request)
    {
        $email = (string) ($request->session()->get('verification_email', ''));

        return view('frontend.verify-email-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Email already verified. Please login.');
        }

        if (empty($user->email_verification_otp) || ! Hash::check($request->otp, $user->email_verification_otp)) {
            return back()->withInput($request->only('email'))->with('error', 'Invalid OTP.');
        }

        if (! $user->email_verification_otp_expires_at || now()->greaterThan($user->email_verification_otp_expires_at)) {
            return back()->withInput($request->only('email'))->with('error', 'OTP expired. Please request a new OTP.');
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_otp' => null,
            'email_verification_otp_expires_at' => null,
        ])->save();

        return redirect()->route('login')->with('success', 'Email verified successfully. You can now login.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Email already verified. Please login.');
        }

        $this->issueEmailOtp($user);

        return back()
            ->with('verification_email', $user->email)
            ->with('success', 'A new OTP has been sent to your email.');
    }

    protected function issueEmailOtp(User $user): void
    {
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_verification_otp' => Hash::make($otp),
            'email_verification_otp_expires_at' => Carbon::now()->addMinutes(10),
        ])->save();

        Mail::raw(
            "Your Ollya verification OTP is: {$otp}. It is valid for 10 minutes.",
            function ($message) use ($user) {
                $message->to($user->email)->subject('Verify your email - OTP');
            }
        );
    }
}