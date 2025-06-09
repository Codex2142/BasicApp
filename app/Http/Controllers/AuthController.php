<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Menampilkan halaman login
    public function index()
    {
        return view('auth.login');
    }

    // Verifikasi Login
    public function loginProcess(Request $request)
    {
        // Username / password
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Mencatat aktivitas login
            activity('login')->causedBy(Auth::user())->log('login');

            // Redirect sesuai role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard.index');
            } elseif (Auth::user()->role === 'user') {
                return redirect()->route('dashboard.index');
            } else {
                Auth::logout();
                return redirect()->route('auth.index')->withErrors(['role' => 'Role tidak dikenali']);
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Mencatat aktivitas logout
        activity('logout')->causedBy(Auth::user())->log('logout');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
