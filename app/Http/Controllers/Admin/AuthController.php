<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): JsonResponse|RedirectResponse
    {
        $identity = trim($request->input('identity', ''));
        $password = $request->input('password', '');
        $remember = $request->boolean('remember', false);
        $isAjax   = $request->ajax() || $request->expectsJson() || $request->filled('ajax_login');

        if (empty($identity) || empty($password)) {
            $msg = 'Please enter both your email/username and password.';
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withInput()->withErrors(['identity' => $msg]);
        }

        $admin = Admin::where('email', $identity)
            ->orWhere('username', $identity)
            ->first();

        if ($admin && Hash::check($password, $admin->password)) {
            Auth::guard('admin')->login($admin, $remember);

            $admin->update(['last_login' => now()]);

            $redirectUrl = route('admin.dashboard');

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'redirect' => $redirectUrl,
                    'admin_name' => $admin->full_name,
                ]);
            }

            return redirect()->intended($redirectUrl);
        }

        $errorMsg = 'Invalid credentials. Please verify your email/username and password.';

        if ($isAjax) {
            return response()->json(['success' => false, 'message' => $errorMsg], 401);
        }

        return back()->withInput()->withErrors(['identity' => $errorMsg]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success_message', 'You have been safely signed out.');
    }
}
