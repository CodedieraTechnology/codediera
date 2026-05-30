<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Unauthorized'])->onlyInput('email');
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function showRegister()
    {
        if (\App\Models\User::where('is_admin', true)->exists()) {
            abort(404);
        }

        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        if (\App\Models\User::where('is_admin', true)->exists()) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_admin' => true,
        ]);

        $superAdminRole = \App\Models\Role::where('slug', 'super_admin')->first();
        if ($superAdminRole) {
            $user->roles()->sync([$superAdminRole->id]);
        }

        return redirect()->route('admin.login')->with('success', 'Super admin account created successfully! Please sign in.');
    }
}

